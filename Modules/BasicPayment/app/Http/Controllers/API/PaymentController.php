<?php

namespace Modules\BasicPayment\app\Http\Controllers\API;

use Exception;
use Illuminate\Support\Str;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use App\Traits\GlobalMailTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Mollie\Laravel\Facades\Mollie;
use Modules\Order\app\Models\Order;
use App\Http\Controllers\Controller;
use Modules\Lawyer\app\Models\Lawyer;
use Illuminate\Support\Facades\Session;
use App\Traits\GetGlobalInformationTrait;
use Illuminate\Support\Facades\Validator;
use Modules\Appointment\app\Models\Appointment;
use Modules\BasicPayment\app\Enums\BasicPaymentSupportedCurrencyListEnum;
use Razorpay\Api\Api;

class PaymentController extends Controller {
    use  GetGlobalInformationTrait, GlobalMailTrait;
    private $paymentService;
    public function __construct() {
        $this->paymentService = app(\Modules\BasicPayment\app\Services\PaymentMethodService::class);
    }
    public function all_payment(): JsonResponse {
        $data = $this->paymentService->getActiveApiGatewaysWithDetails();
        if ($data) {
            return response()->json(['status' => 'success', 'data' => $data], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function bank_information(): JsonResponse {
        $method = $this->paymentService::BANK_PAYMENT;
        $bank_information = $this->paymentService->getGatewayDetails($method)->bank_information ?? '';
        return response()->json(['status' => 'success', 'data' => $bank_information], 200);
    }
    public function support_currency($method): JsonResponse {
        if (!in_array($method, ['paypal', 'stripe', 'mollie', 'razorpay', 'flutterwave', 'paystack'])) {
            return response()->json(['status' => 'error', 'message' => __('Method Not Found')], 404);
        }
        $currency_code = strtoupper(request()->query('currency', 'USD'));

        if (!$this->paymentService->isCurrencySupported($method, $currency_code)) {
            $supportedCurrencies = $this->paymentService->getSupportedCurrencies($method);
            return response()->json([
                'status'  => 'error',
                'message' => __('You are trying to use unsupported currency'),
                'support' => implode(', ', $supportedCurrencies),
            ], 400);
        }
        return response()->json(['status' => 'success'], 200);
    }
    public function pay_via_bank(Request $request) {
        $method = $this->paymentService::BANK_PAYMENT;

        $user = auth()->guard('api')->user();
        $payable_currency = strtoupper(request()->query('currency', 'USD'));
        $totalAppointment = ShoppingCart::where('user_id', $user->id)->get();

        if ($totalAppointment->count() == 0) {
            return response()->json([
                'status'  => 'error',
                'message' => __('No appointment found.'),
            ], 404);

        }
        if (!$this->paymentService->isActive($method)) {
            return response()->json([
                'status'  => 'error',
                'message' => __('The selected payment method is now inactive.'),
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'bank_name'      => 'required|string|max:190',
            'account_number' => 'required|string|max:190',
            'routing_number' => 'nullable|string|max:190',
            'branch'         => 'nullable|string|max:190',
            'transaction'    => 'required|string',
        ], [
            'bank_name.required'      => __('Bank Name is required.'),
            'account_number.required' => __('Account Number is required.'),
            'routing_number.required' => __('Routing Number is required.'),
            'branch.required'         => __('Branch is required.'),
            'transaction.required'    => __('Transaction is required.'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }

        $payment_description = json_encode($request->only(['bank_name', 'account_number', 'routing_number', 'branch', 'transaction']));

        $allPayments = Order::whereNotNull('payment_description')->get();

        foreach ($allPayments as $payment) {
            $paymentDetailsJson = json_decode($payment->payment_description, true);

            if (isset($paymentDetailsJson['account_number']) && $paymentDetailsJson['account_number'] == $request->account_number) {
                if (isset($paymentDetailsJson['transaction']) && $paymentDetailsJson['transaction'] == $request->transaction) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => __('Payment failed, transaction already exist'),
                    ], 400);
                }
            }
        }

        try {
            $payable_amount = $totalAppointment->sum('price');
            $calculatePayableCharge = $this->paymentService->getApiPayableAmount($method, $payable_amount, $payable_currency);

            DB::beginTransaction();

            $paid_amount = $calculatePayableCharge?->payable_amount + $calculatePayableCharge?->gateway_charge;

            $order = Order::create([
                'order_id'               => Str::random(10),
                'user_id'                => $user->id,
                'appointment_qty'        => $totalAppointment->sum('qty'),
                'payment_method'         => $method,
                'payment_status'         => 0,
                'amount_usd'             => $paid_amount,
                'total_payment'          => apiCurrency($payable_currency,$payable_amount, false),
                'gateway_charge'         => $calculatePayableCharge?->gateway_charge,
                'payable_with_charge'    => $calculatePayableCharge?->payable_with_charge,
                'payable_currency'       => $payable_currency,
                'paid_amount'            => $paid_amount,
                'payment_transaction_id' => $request->transaction,
                'payment_description'    => $payment_description,
            ]);

            $order_details = "";
            foreach ($totalAppointment as $item) {
                Appointment::create([
                    'user_id'             => $user->id,
                    'order_id'            => $order->id,
                    'day_id'              => $item->day_id,
                    'schedule_id'         => $item->schedule_id,
                    'lawyer_id'           => $item->lawyer_id,
                    'date'                => $item->date,
                    'appointment_fee_usd' => $item->price,
                    'appointment_fee'     => apiCurrency($payable_currency, $item->price, false),
                    'payable_currency'    => $calculatePayableCharge?->currency_code,
                    'payment_method'      => $method,

                ]);

                $lawyer = Lawyer::find($item->lawyer_id);
                $order_details .= 'Lawyer: ' . $lawyer?->name . '<br>';
                $order_details .= 'Email: ' . $lawyer?->email . '<br>';
                $order_details .= 'Phone: ' . $lawyer?->phone . '<br>';
                $order_details .= 'Schedule: ' . $item?->time . '<br>';
                $order_details .= 'Price: ' . apiCurrency($payable_currency, $item->price, false) . '<br><hr>';
            }
            DB::commit();

            ShoppingCart::where('user_id', $user->id)->delete();

            // send mail
            try {
                $total_amount = specific_currency_with_icon($order->payable_currency, $order->total_payment);
                [$subject, $message] = $this->fetchEmailTemplate('order_mail', ['client_name' => $user->name, 'orderId' => $order->order_id, 'payment_method' => $method, 'amount' => $total_amount, 'payment_status' => 'Pending', 'status' => 'Pending', 'order_details' => $order_details]);
                $this->sendMail($user->email, $subject, $message);
            } catch (Exception $e) {
                info($e->getMessage());
            }
            $this->paymentService->removeSessions();

            $image = 'success.png';
            $title = __('Order created successfully.');
            $sub_title = __('For check more details you can go to your dashboard');
            return view('api.order_notification', compact('image', 'title', 'sub_title'));

        } catch (Exception $e) {
            DB::rollBack();
            info($e->getMessage());
            return to_route('payment-api.webview-failed-payment');
        }
    }
    public function placeOrder($paymentMethod) {
        $token = request()->bearerToken();
        $user = auth()->guard('api')->user();
        if(!$user){
            return response()->json(['status' => 'error', 'message' => __('Unauthorized')], 401);
        }

        $payable_currency = strtoupper(request()->query('currency', 'USD'));
        $totalAppointment = ShoppingCart::where('user_id', $user->id)->get();

        if ($totalAppointment->count() == 0) {
            return response()->json([
                'status'  => 'error',
                'message' => __('No appointment found.'),
            ], 404);

        }
        if (!$this->paymentService->isActive($paymentMethod)) {
            return response()->json([
                'status'  => 'error',
                'message' => __('The selected payment method is now inactive.'),
            ], 400);
        }
        if (!$this->paymentService->isCurrencySupported($paymentMethod,$payable_currency)) {
            $supportedCurrencies = $this->paymentService->getSupportedCurrencies($paymentMethod);
            return response()->json([
                'status'  => 'error',
                'message' => __('You are trying to use unsupported currency'),
                'support' => implode(', ', $supportedCurrencies),
            ], 400);
        }

        try {
            $payable_amount = $totalAppointment->sum('price');
            $calculatePayableCharge = $this->paymentService->getApiPayableAmount($paymentMethod, $payable_amount, $payable_currency);

            DB::beginTransaction();

            $paid_amount = $calculatePayableCharge?->payable_with_charge + $calculatePayableCharge?->gateway_charge;
            if (in_array($paymentMethod, ['Razorpay', 'Stripe'])) {
                $allCurrencyCodes = BasicPaymentSupportedCurrencyListEnum::getStripeSupportedCurrencies();

                if (in_array(Str::upper($calculatePayableCharge?->currency_code), $allCurrencyCodes['non_zero_currency_codes'])) {
                    $paid_amount = $paid_amount;
                } elseif (in_array(Str::upper($calculatePayableCharge?->currency_code), $allCurrencyCodes['three_digit_currency_codes'])) {
                    $paid_amount = (int) rtrim(strval($paid_amount), '0');
                } else {
                    $paid_amount = floatval($paid_amount / 100);
                }
            }

            $order = Order::create([
                'order_id'            => Str::random(10),
                'user_id'             => $user->id,
                'appointment_qty'     => $totalAppointment->sum('qty'),
                'payment_method'      => $paymentMethod,
                'payment_status'      => 0,
                'amount_usd'          => $payable_amount,
                'total_payment'       => apiCurrency($payable_currency, $payable_amount, false),
                'gateway_charge'         => $calculatePayableCharge?->gateway_charge,
                'payable_with_charge'    => $calculatePayableCharge?->payable_with_charge,
                'payable_currency'       => $payable_currency,
                'paid_amount'            => $paid_amount,
            ]);

            $order_details = "";
            foreach ($totalAppointment as $item) {
                Appointment::create([
                    'user_id'             => $user->id,
                    'order_id'            => $order->id,
                    'day_id'              => $item->day_id,
                    'schedule_id'         => $item->schedule_id,
                    'lawyer_id'           => $item->lawyer_id,
                    'date'                => $item->date,
                    'appointment_fee_usd' => $item->price,
                    'appointment_fee'     => apiCurrency($payable_currency, $item->price, false),
                    'payable_currency'    => $calculatePayableCharge?->currency_code,
                    'payment_method'      => $paymentMethod,

                ]);

                $lawyer = Lawyer::find($item->lawyer_id);
                $order_details .= 'Lawyer: ' . $lawyer?->name . '<br>';
                $order_details .= 'Email: ' . $lawyer?->email . '<br>';
                $order_details .= 'Phone: ' . $lawyer?->phone . '<br>';
                $order_details .= 'Schedule: ' . $item?->time . '<br>';
                $order_details .= 'Price: ' . apiCurrency($payable_currency, $item->price, false) . '<br><hr>';
            }
            DB::commit();

            ShoppingCart::where('user_id', $user->id)->delete();

            // send mail
            try {
                $total_amount = specific_currency_with_icon($order->payable_currency, $order->total_payment);
                [$subject, $message] = $this->fetchEmailTemplate('order_mail', ['client_name' => $user->name, 'orderId' => $order->order_id, 'payment_method' => $paymentMethod, 'amount' => $total_amount, 'payment_status' => 'Pending', 'status' => 'Pending', 'order_details' => $order_details]);
                $this->sendMail($user->email, $subject, $message);
            } catch (Exception $e) {
                info($e->getMessage());
            }


            $order_id = $order?->order_id;

            return response()->json(['status'=>'success','url'=>route('payment-api.payment',['token'=>$token,'order_id'=>$order_id])],200);
        } catch (Exception $e) {
            DB::rollBack();
            info($e->getMessage());
            return to_route('payment-api.webview-failed-payment');
        }
    }
    public function payment(Request $request) {
        $token = $request?->token ?? null;
        $request->headers->set('Authorization', 'Bearer ' . $token);
        $user = auth()->guard('api')->user();
        if(!$user){
            // abort(403);
            return response()->json(['status' => 'error', 'message' => __('Unauthorized')], 401);

        }
        $order_id = $request?->order_id ?? null;
        $order = $user?->orders()->where('order_id', $order_id) ->pendingOrder()->first();

        if (!$order) {
            abort(404);
        }
        $paymentMethod = $order->payment_method;
        if (!$this->paymentService->isActive($paymentMethod)) {
            return response()->json(['status' => 'error', 'message' => __('The selected payment method is now inactive.')], 400);
        }

        $calculatePayableCharge = $this->paymentService->getApiPayableAmount($paymentMethod, $order?->amount_usd,$order?->payable_currency);

        Session::put('order', $order);
        Session::put('payable_currency', $order?->payable_currency);
        Session::put('paid_amount', $calculatePayableCharge?->payable_with_charge);

        $paymentService = $this->paymentService;
        $view = $this->paymentService->getBladeView($paymentMethod);
        return view($view, compact('order', 'paymentService', 'paymentMethod','user','token','order_id'));
    }
    public function payment_success() {
        $order = session()->get('order');
        $after_success_transaction = session()->get('after_success_transaction', null);
        $payment_details = session()->get('payment_details', null);
        $payable_with_charge = session()->get('payable_with_charge', $order->payable_with_charge);
        $gateway_charge = session()->get('gateway_charge', $order->gateway_charge);
        $paid_amount = session()->get('paid_amount', $order->paid_amount);

        try {
            DB::beginTransaction();
            $checkNotBank = $order->payment_method == $this->paymentService::BANK_PAYMENT ? 0 : 1;
            $payment_details = $checkNotBank ? $payment_details : json_encode($payment_details);

            $order->gateway_charge = $gateway_charge;
            $order->payable_with_charge = $payable_with_charge;
            $order->paid_amount = $paid_amount;
            $order->payment_status = $checkNotBank;
            $order->order_status = $checkNotBank;
            $order->payment_transaction_id = $after_success_transaction;
            $order->payment_description = $payment_details;
            $order->approved_date = $checkNotBank ? now() : null;
            $order->save();

            $order->appointments()->update([
                'payment_status'         => $checkNotBank,
                'payment_transaction_id' => $after_success_transaction,
                'payment_description'    => $payment_details,
            ]);

            if($checkNotBank){
                $appointments = $order->appointments()->with('lawyer')->get();

                foreach ($appointments as $item) {
                    Lawyer::where('id', $item?->lawyer?->id)->increment('wallet_balance', $item?->appointment_fee_usd);
                }
            }
            DB::commit();

            try {
                $user = auth()->guard('api')->user();
                [$subject, $message] = $this->fetchEmailTemplate('approve_payment', ['client_name' => $user->name, 'orderId' => "#$order->order_id"]);
                $this->sendMail($user->email, $subject, $message);
            } catch (Exception $e) {
                info($e->getMessage());
            }

            $this->paymentService->removeSessions();

            $image = 'success.png';
            $title = __('Order created successfully.');
            $sub_title = __('For check more details you can go to your dashboard');
            return view('api.order_notification', compact('image', 'title', 'sub_title'));
        } catch (Exception $e) {
            DB::rollback();
            info($e->getMessage());
            $image = 'cancel.png';
            $title = __('Your order has been cancelled');
            $sub_title = __('Please try again if you wish to place a new order.');
            return view('api.order_notification', compact('image', 'title', 'sub_title'));
        }
    }
    public function payment_failed() {
        $this->paymentService->removeSessions();
        $image = 'cancel.png';
        $title = __('Your order has been cancelled');
        $sub_title = __('Please try again if you wish to place a new order.');
        return view('api.order_notification', compact('image', 'title', 'sub_title'));
    }
    public function stripe_pay() {
        $basic_payment = $this->get_basic_payment_info();

        $user = auth()->guard('api')->user();
        $order_id = request('order_id', null);
        $order = $user?->orders()->where('order_id', $order_id)->pendingOrder()->first();
        if (!$order) {
            return response()->json([
                'status'  => 'error',
                'message' => __('Not Found!'),
            ], 404);
        }
        session()->put('order',$order);
        $payable_amount = $order->amount_usd;
        $currency_code = $order->payable_currency;

        // Set your Stripe API secret key
        \Stripe\Stripe::setApiKey($basic_payment->stripe_secret);

        $calculate_payable_charge = $this->paymentService->getApiPayableAmount($this->paymentService::STRIPE, $payable_amount, $currency_code);
        $payable_with_charge = $calculate_payable_charge?->payable_amount + $calculate_payable_charge?->gateway_charge;

        $allCurrencyCodes = BasicPaymentSupportedCurrencyListEnum::getStripeSupportedCurrencies();

        if (in_array(Str::upper($currency_code), $allCurrencyCodes['non_zero_currency_codes'])) {
            $payable_with_charge = $calculate_payable_charge->payable_with_charge;
        } elseif (in_array(Str::upper($currency_code), $allCurrencyCodes['three_digit_currency_codes'])) {
            $convertedCharge = (string) $calculate_payable_charge->payable_with_charge . '0';
            $payable_with_charge = (int) $convertedCharge;
        } else {
            $payable_with_charge = (int) ($calculate_payable_charge->payable_with_charge * 100);
        }

        $after_failed_url = route('payment-api.webview-failed-payment');

        Session::put('payable_amount', $calculate_payable_charge->payable_amount);
        Session::put('payable_with_charge', $payable_with_charge);
        Session::put('after_failed_url', $after_failed_url);

        // Create a checkout session
        $checkoutSession = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [[
                'price_data' => [
                    'currency'     => $currency_code,
                    'unit_amount'  => $payable_with_charge,
                    'product_data' => [
                        'name' => cache()->get('setting')->app_name,
                    ],
                ],
                'quantity'   => 1,
            ]],
            'mode'                 => 'payment',
            'success_url'          => url('/stripe-webview') . '?session_id={CHECKOUT_SESSION_ID}&bearer_token=' . request()->bearer_token,
            'cancel_url'           => $after_failed_url,
        ]);

        // Redirect to the checkout session URL
        return redirect()->away($checkoutSession->url);
    }
    public function stripe_success(Request $request) {
        $after_success_url = route('payment-api.webview-success-payment', ['bearer_token' => request()->bearer_token]);

        $basic_payment = $this->get_basic_payment_info();

        // Assuming the Checkout Session ID is passed as a query parameter
        $session_id = $request->query('session_id');
        if ($session_id) {
            \Stripe\Stripe::setApiKey($basic_payment->stripe_secret);

            $session = \Stripe\Checkout\Session::retrieve($session_id);

            $paymentDetails = [
                'transaction_id' => $session->payment_intent,
                'amount'         => $session->amount_total,
                'currency'       => $session->currency,
                'payment_status' => $session->payment_status,
                'created'        => $session->created,
            ];

            Session::put('after_success_url', $after_success_url);
            Session::put('after_success_transaction', $session->payment_intent);
            Session::put('payment_details', $paymentDetails);

            return redirect($after_success_url);
        }

        $after_failed_url = Session::get('after_failed_url');
        return redirect($after_failed_url);
    }
    public function pay_via_mollie() {
        $user = auth()->guard('api')->user();
        $order_id = request('order_id', null);
        $order = $user?->orders()->where('order_id', $order_id)->pendingOrder()->first();
        if (!$order) {
            return response()->json([
                'status'  => 'error',
                'message' => __('Not Found!'),
            ], 404);
        }
        session()->put('order',$order);
        $payable_amount = $order->amount_usd;
        $currency_code = $order->payable_currency;

        $after_success_url = route('payment-api.webview-success-payment', ['bearer_token' => request()->bearer_token]);
        $after_failed_url = route('payment-api.webview-failed-payment');

        $basic_payment = $this->get_basic_payment_info();

        $mollie_credentials = (object) [
            'mollie_key' => $basic_payment->mollie_key,
        ];

        return $this->pay_with_mollie($mollie_credentials, $payable_amount, $currency_code, $after_success_url, $after_failed_url, $user);
    }
    public function pay_with_mollie($mollie_credentials, $payable_amount, $currency_code, $after_success_url, $after_failed_url, $user) {

        $calculate_payable_charge = $this->paymentService->getApiPayableAmount($this->paymentService::MOLLIE, $payable_amount, $currency_code);

        try {
            Mollie::api()->setApiKey($mollie_credentials->mollie_key);
            $payment = Mollie::api()->payments()->create([
                'amount'      => [
                    'currency' => '' . strtoupper($currency_code) . '',
                    'value'    => '' . $calculate_payable_charge?->payable_with_charge . '',
                ],
                'description' => cache()->get('setting')->app_name,
                'redirectUrl' => route('payment-api.mollie-success', ['bearer_token' => request()->bearer_token]),
            ]);

            $payment = Mollie::api()->payments()->get($payment->id);

            session()->put('payment_id', $payment->id);
            session()->put('after_success_url', $after_success_url);
            session()->put('after_failed_url', $after_failed_url);
            session()->put('payable_amount', $payable_amount);
            session()->put('mollie_credentials', $mollie_credentials);
            session()->put('payable_with_charge', $calculate_payable_charge->payable_with_charge);
            session()->put('paid_amount', $calculate_payable_charge->payable_amount);

            return redirect($payment->getCheckoutUrl(), 303);

        } catch (Exception $ex) {
            info($ex->getMessage());
            $image = 'cancel.png';
            $title = __('Your order has been cancelled');
            $sub_title = __('Please try again if you wish to place a new order.');
            return view('api.order_notification', compact('image', 'title', 'sub_title'));
        }

    }
    public function mollie_success() {

        $mollie_credentials = Session::get('mollie_credentials');

        Mollie::api()->setApiKey($mollie_credentials->mollie_key);
        $payment = Mollie::api()->payments->get(session()->get('payment_id'));
        if ($payment->isPaid()) {
            $paymentDetails = [
                'transaction_id' => $payment->id,
                'amount'         => $payment->amount->value,
                'currency'       => $payment->amount->currency,
                'fee'            => $payment->settlementAmount->value . ' ' . $payment->settlementAmount->currency,
                'description'    => $payment->description,
                'payment_method' => $payment->method,
                'status'         => $payment->status,
                'paid_at'        => $payment->paidAt,
            ];

            Session::put('payment_details', $paymentDetails);
            Session::put('after_success_transaction', session()->get('payment_id'));

            $after_success_url = Session::get('after_success_url');

            return redirect($after_success_url);

        } else {
            $after_failed_url = Session::get('after_failed_url');

            return redirect($after_failed_url);
        }
    }
    public function pay_via_razorpay(Request $request) {
        $payment_setting = $this->get_basic_payment_info();

        $after_success_url = route('payment-api.webview-success-payment', ['bearer_token' => request()->bearer_token]);
        $after_failed_url = route('payment-api.webview-failed-payment');

        $user = auth()->guard('api')->user();
        $order_id = request('order_id', null);
        $order = $user?->orders()->where('order_id', $order_id)->pendingOrder()->first();
        if (!$order) {
            return response()->json([
                'status'  => 'error',
                'message' => __('Not Found!'),
            ], 404);
        }
        session()->put('order',$order);

        $razorpay_credentials = (object) [
            'razorpay_key'    => $payment_setting->razorpay_key,
            'razorpay_secret' => $payment_setting->razorpay_secret,
        ];

        return $this->pay_with_razorpay($request, $razorpay_credentials, $after_success_url, $after_failed_url);

    }
    public function pay_with_razorpay(Request $request, $razorpay_credentials, $after_success_url, $after_failed_url) {
        $input = $request->all();
        info($input);
        $api = new Api($razorpay_credentials->razorpay_key, $razorpay_credentials->razorpay_secret);
        // why i need to call this? //
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        if (count($input) && !empty($input['razorpay_payment_id'])) {
            try {
                // if i am calling thise here? TODO: i will test this in future update //
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(['amount' => $payment['amount']]);

                $paymentDetails = [
                    'transaction_id' => $response->id,
                    'amount'         => $response->amount,
                    'currency'       => $response->currency,
                    'fee'            => $response->fee,
                    'description'    => $response->description,
                    'payment_method' => $response->method,
                    'status'         => $response->status,
                ];

                Session::put('after_success_transaction', $response->id);
                Session::put('payment_details', $paymentDetails);

                return redirect($after_success_url);

            } catch (Exception $e) {
                info($e->getMessage());
                return redirect($after_failed_url);
            }
        } else {
            return redirect($after_failed_url);
        }

    }
    public function flutterwave_payment(Request $request) {
        $user = auth()->guard('api')->user();
        $order_id = request('order_id', null);
        $order = $user?->orders()->where('order_id', $order_id)->pendingOrder()->first();
        if (!$order) {
            return response()->json([
                'status'  => 'error',
                'message' => __('Not Found!'),
            ], 404);
        }
        session()->put('order',$order);

        $payment_setting = $this->get_basic_payment_info();
        $curl = curl_init();
        $tnx_id = $request->tnx_id;
        $url = "https://api.flutterwave.com/v3/transactions/$tnx_id/verify";
        $token = $payment_setting->flutterwave_secret_key;
        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                "Authorization: Bearer $token",
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        if ($response->status == 'success') {
            $paymentDetails = [
                'status'            => $response->status,
                'trx_id'            => $tnx_id,
                'amount'            => $response?->data?->amount,
                'amount_settled'    => $response?->data?->amount_settled,
                'currency'          => $response?->data?->currency,
                'charged_amount'    => $response?->data?->charged_amount,
                'app_fee'           => $response?->data?->app_fee,
                'merchant_fee'      => $response?->data?->merchant_fee,
                'card_last_4digits' => $response?->data?->card?->last_4digits,
            ];

            Session::put('payment_details', $paymentDetails);
            Session::put('after_success_transaction', $tnx_id);

            $image = 'success.png';
            $title = __('Order created successfully.');
            $sub_title = __('For check more details you can go to your dashboard');
            return view('api.order_notification', compact('image', 'title', 'sub_title'));

        } else {

            $image = 'cancel.png';
            $title = __('Your order has been cancelled');
            $sub_title = __('Please try again if you wish to place a new order.');
            return view('api.order_notification',compact('image','title','sub_title'));
        }

    }
    public function paystack_payment(Request $request) {
        $user = auth()->guard('api')->user();
        $order_id = request('order_id', null);
        $order = $user?->orders()->where('order_id', $order_id)->pendingOrder()->first();
        if (!$order) {
            return response()->json([
                'status'  => 'error',
                'message' => __('Not Found!'),
            ], 404);
        }
        session()->put('order',$order);

        $payment_setting = $this->get_basic_payment_info();

        $reference = $request->reference;
        $transaction = $request->tnx_id;
        $secret_key = $payment_setting->paystack_secret_key;
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => "https://api.paystack.co/transaction/verify/$reference",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer $secret_key",
                'Cache-Control: no-cache',
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $final_data = json_decode($response);
        if ($final_data->status == true) {
            $paymentDetails = [
                'status'             => $final_data?->data?->status,
                'transaction_id'     => $transaction,
                'requested_amount'   => $final_data?->data->requested_amount,
                'amount'             => $final_data?->data?->amount,
                'currency'           => $final_data?->data?->currency,
                'gateway_response'   => $final_data?->data?->gateway_response,
                'paid_at'            => $final_data?->data?->paid_at,
                'card_last_4_digits' => $final_data?->data->authorization?->last4,
            ];

            Session::put('payment_details', $paymentDetails);
            Session::put('after_success_transaction', $transaction);

            return response()->json(['message' => 'payment success']);
        } else {
            $notification = __('Something went wrong, please try again');
            return response()->json(['message' => $notification], 403);
        }
    }
    public function pay_via_instamojo() {
        $user = auth()->guard('api')->user();
        $order_id = request('order_id', null);
        $order = $user?->orders()->where('order_id', $order_id)->pendingOrder()->first();
        if (!$order) {
            return response()->json([
                'status'  => 'error',
                'message' => __('Not Found!'),
            ], 404);
        }
        $payable_amount = $order->amount_usd;
        $currency_code = $order->payable_currency;
        session()->put('order',$order);
        session()->put('payable_currency',$currency_code);
        session()->put('paid_amount',$payable_amount);

        $after_success_url = route('payment-success');
        $after_failed_url = route('payment-failed');

        session()->put('after_success_url', $after_success_url);
        session()->put('after_failed_url', $after_failed_url);

        $payment_setting = $this->get_basic_payment_info();

        $instamojo_credentials = (object) [
            'instamojo_api_key'    => $payment_setting->instamojo_api_key,
            'instamojo_auth_token' => $payment_setting->instamojo_auth_token,
            'account_mode'         => $payment_setting->instamojo_account_mode,
        ];

        return $this->pay_with_instamojo($instamojo_credentials,$user);
    }
    public function pay_with_instamojo($instamojo_credentials,$user) {
        $payable_currency = session()->get('payable_currency');
        $paid_amount = session()->get('paid_amount');

        $calculate_payable_charge = $this->paymentService->getApiPayableAmount($this->paymentService::INSTAMOJO, $paid_amount, $payable_currency);
        $payable_with_charge = $calculate_payable_charge?->payable_amount + $calculate_payable_charge?->gateway_charge;

        $environment = $instamojo_credentials->account_mode;
        $api_key = $instamojo_credentials->instamojo_api_key;
        $auth_token = $instamojo_credentials->instamojo_auth_token;

        if ($environment == 'Sandbox') {
            $url = 'https://test.instamojo.com/api/1.1/';
        } else {
            $url = 'https://www.instamojo.com/api/1.1/';
        }

        try {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url . 'payment-requests/');
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER,
                ["X-Api-Key:$api_key",
                    "X-Auth-Token:$auth_token"]);
            $payload = [
                'purpose'                 => env('APP_NAME'),
                'amount'                  => $payable_with_charge,
                'phone'                   => '918160651749',
                'buyer_name'              => $user?->name,
                'redirect_url'            => route('payment-api.instamojo-success', ['bearer_token' => request()->bearer_token]),
                'send_email'              => true,
                'webhook'                 => 'http://www.example.com/webhook/',
                'send_sms'                => true,
                'email'                   => $user?->email,
                'allow_repeated_payments' => false,
            ];
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response);
            session()->put('instamojo_credentials', $instamojo_credentials);

            if (!empty($response?->payment_request?->longurl)) {
                return redirect($response?->payment_request?->longurl);
            } else {
                $after_failed_url = Session::get('after_failed_url');
                return redirect($after_failed_url);
            }

        } catch (Exception $ex) {
            $after_failed_url = Session::get('after_failed_url');
            return redirect($after_failed_url);
        }

    }
    public function instamojo_success(Request $request) {

        $instamojo_credentials = Session::get('instamojo_credentials');

        $input = $request->all();
        $environment = $instamojo_credentials->account_mode;
        $api_key = $instamojo_credentials->instamojo_api_key;
        $auth_token = $instamojo_credentials->instamojo_auth_token;

        if ($environment == 'Sandbox') {
            $url = 'https://test.instamojo.com/api/1.1/';
        } else {
            $url = 'https://www.instamojo.com/api/1.1/';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . 'payments/' . $request->get('payment_id'));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            ["X-Api-Key:$api_key",
                "X-Auth-Token:$auth_token"]);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            $after_failed_url = Session::get('after_failed_url');

            return redirect($after_failed_url);
        } else {
            $data = json_decode($response);
        }

        if ($data->success == true) {
            if ($data->payment->status == 'Credit') {
                Session::put('after_success_transaction', $request->get('payment_id'));
                Session::put('payment_details', $request->get('payment_id'));
                $after_success_url = Session::get('after_success_url');

                return redirect($after_success_url);
            }
        } else {
            $after_failed_url = Session::get('after_failed_url');

            return redirect($after_failed_url);
        }
    }

}

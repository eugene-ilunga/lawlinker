<?php

namespace Modules\BasicPayment\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\GetGlobalInformationTrait;
use App\Traits\GlobalMailTrait;
use Closure;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\Appointment\app\Models\Appointment;
use Modules\BasicPayment\app\Enums\BasicPaymentSupportedCurrencyListEnum;
use Modules\BasicPayment\app\Http\Controllers\FrontPaymentController;
use Modules\Lawyer\app\Models\Lawyer;
use Modules\Order\app\Models\Order;
use Razorpay\Api\Api;
use App\Http\Requests\BankInformationRequest;

class PaymentController extends Controller {
    use GetGlobalInformationTrait, GlobalMailTrait;
    private $paymentService;
    public function __construct() {
        $this->paymentService = app(\Modules\BasicPayment\app\Services\PaymentMethodService::class);
        $this->middleware(function (Request $request, Closure $next) {
            if (session()->has('order') || Route::is('payment') || Route::is('place.order')) {
                return $next($request);
            }
            return redirect()->back()->with(['message' => __('Not Found!'), 'alert-type' => 'error']);
        });
    }
    public function placeOrder($method) {
        $user = userAuth();

        if (Cart::count() == 0) {
            $this->paymentService->removeSessions();
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => __('No appointment found.')]);
            } else {
                return redirect()->back()->with(['message' => __('No appointment found.'), 'alert-type' => 'error']);
            }
        }

        $activeGateways = array_keys($this->paymentService->getActiveGatewaysWithDetails());
        if (!in_array($method, $activeGateways)) {
            return response()->json(['status' => false, 'message' => __('The selected payment method is now inactive.')]);
        }
        if (!$this->paymentService->isCurrencySupported($method)) {
            $supportedCurrencies = $this->paymentService->getSupportedCurrencies($method);
            return response()->json(['status' => false, 'message' => __('You are trying to use unsupported currency'), 'supportCurrency' => sprintf(
                '%s %s: %s',
                strtoupper($method),
                __('supports only these types of currencies'),
                implode(', ', $supportedCurrencies)
            )]);
        }

        try {
            $payable_amount = Session::get('payable_amount');
            $calculatePayableCharge = $this->paymentService->getPayableAmount($method, $payable_amount);

            DB::beginTransaction();

            $paid_amount = $calculatePayableCharge?->payable_amount + $calculatePayableCharge?->gateway_charge;

            if (in_array($method, ['Razorpay', 'Stripe'])) {
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
                'appointment_qty'     => Cart::count(),
                'payment_method'      => $method,
                'payment_status'      => 0,
                'amount_usd'          => $payable_amount,
                'total_payment'       => currency($payable_amount, false),
                'gateway_charge'      => $calculatePayableCharge?->gateway_charge,
                'payable_with_charge' => $calculatePayableCharge?->payable_with_charge,
                'payable_currency'    => $calculatePayableCharge?->currency_code,
                'paid_amount'         => $paid_amount,
            ]);

            $order_details = "";
            foreach (Cart::content() as $item) {
                Appointment::create([
                    'user_id'             => $user->id,
                    'order_id'            => $order->id,
                    'day_id'              => $item->options->day_id,
                    'schedule_id'         => $item->options->schedule_id,
                    'lawyer_id'           => $item->options->lawyer_id,
                    'date'                => $item->options->date,
                    'appointment_fee_usd' => $item->price,
                    'appointment_fee'     => currency($item->price, false),
                    'payable_currency'    => $calculatePayableCharge?->currency_code,
                    'payment_method'      => $method,

                ]);

                $lawyer = Lawyer::find($item->options->lawyer_id);
                $order_details .= 'Lawyer: ' . $lawyer?->name . '<br>';
                $order_details .= 'Email: ' . $lawyer?->email . '<br>';
                $order_details .= 'Phone: ' . $lawyer?->phone . '<br>';
                $order_details .= 'Schedule: ' . $item?->options?->time . '<br>';
                $order_details .= 'Price: ' . currency($item->price) . '<br><hr>';
            }
            DB::commit();

            Cart::destroy();

            // send mail
            try {
                $total_amount = specific_currency_with_icon($order->payable_currency, $order->total_payment);
                [$subject, $message] = $this->fetchEmailTemplate('order_mail', ['client_name' => $user->name, 'orderId' => $order->order_id, 'payment_method' => $method, 'amount' => $total_amount,'payment_status' => 'Pending','status' => 'Pending', 'order_details' => $order_details]);
                $this->sendMail($user->email, $subject, $message);
            } catch (Exception $e) {
                info($e->getMessage());
            }

            return response()->json(['success' => true, 'order_id' => $order?->order_id]);
        } catch (Exception $e) {
            DB::rollBack();
            info($e->getMessage());
            return response()->json(['status' => false, 'message' => __('Payment Failed')]);
        }

    }
    public function index() {
        $order_id = request('order_id', null);
        $user = userAuth();

        $order = $user?->orders()->where('order_id', $order_id) ->pendingOrder()->first();

        if (!$order) {
            $notification = [
                'message'    => __('Not Found!'),
                'alert-type' => 'error',
            ];
            return redirect()->back()->with($notification);
        }
        $paymentMethod = $order->payment_method;
        if (!$this->paymentService->isActive($paymentMethod)) {
            $notification = [
                'message'    => __('The selected payment method is now inactive.'),
                'alert-type' => 'error',
            ];
            return redirect()->back()->with($notification);
        }

        $calculatePayableCharge = $this->paymentService->getPayableAmount($paymentMethod, $order?->amount_usd,$order?->payable_currency);

        Session::put('order', $order);
        Session::put('payable_currency', $order?->payable_currency);
        Session::put('paid_amount', $calculatePayableCharge?->payable_with_charge);

        $paymentService = $this->paymentService;
        $view = $this->paymentService->getBladeView($paymentMethod);
        return view($view, compact('order', 'paymentService', 'paymentMethod','user'));
    }
    public function pay_via_bank(BankInformationRequest $request) {
        $bankDetails = json_encode($request->only(['bank_name', 'account_number', 'routing_number', 'branch', 'transaction']));

        $allPayments = Order::whereNotNull('payment_description')->get();

        foreach ($allPayments as $payment) {
            $paymentDetailsJson = json_decode($payment->payment_description, true);

            if (isset($paymentDetailsJson['account_number']) && $paymentDetailsJson['account_number'] == $request->account_number) {
                if (isset($paymentDetailsJson['transaction']) && $paymentDetailsJson['transaction'] == $request->transaction) {
                    $notification = __('Payment failed, transaction already exist');
                    $notification = ['message' => $notification, 'alert-type' => 'error'];

                    return redirect()->back()->with($notification);
                }
            }
        }
        Session::put('after_success_transaction', $request->transaction);
        Session::put('payment_details', $bankDetails);

        return $this->payment_success();
    }
    public function pay_via_paypal() {
        $basic_payment = $this->get_basic_payment_info();
        $paypal_credentials = (object) [
            'paypal_client_id'    => $basic_payment->paypal_client_id,
            'paypal_secret_key'   => $basic_payment->paypal_secret_key,
            'paypal_account_mode' => $basic_payment->paypal_account_mode,
        ];

        $after_success_url = route('payment-success');
        $after_failed_url = route('payment-failed');

        $paypal_payment = new FrontPaymentController();

        return $paypal_payment->pay_with_paypal($paypal_credentials, $after_success_url, $after_failed_url);
    }
    public function pay_via_stripe() {
        $basic_payment = $this->get_basic_payment_info();

        // Set your Stripe API secret key
        \Stripe\Stripe::setApiKey($basic_payment?->stripe_secret);

        $after_failed_url = route('payment-failed');

        session()->put('after_failed_url', $after_failed_url);

        $payable_currency = session()->get('payable_currency');
        $paid_amount = session()->get('paid_amount');

        $allCurrencyCodes = $this->paymentService->getSupportedCurrencies($this->paymentService::STRIPE);

        if (in_array(Str::upper($payable_currency), $allCurrencyCodes['non_zero_currency_codes'])) {
            $payable_with_charge = $paid_amount;
        } elseif (in_array(Str::upper($payable_currency), $allCurrencyCodes['three_digit_currency_codes'])) {
            $convertedCharge = (string) $paid_amount . '0';
            $payable_with_charge = (int) $convertedCharge;
        } else {
            $payable_with_charge = (int) ($paid_amount * 100);
        }

        // Create a checkout session
        $checkoutSession = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [[
                'price_data' => [
                    'currency'     => $payable_currency,
                    'unit_amount'  => $payable_with_charge,
                    'product_data' => [
                        'name' => cache()->get('setting')->app_name,
                    ],
                ],
                'quantity'   => 1,
            ]],
            'mode'                 => 'payment',
            'success_url'          => url('/pay-via-stripe') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'           => $after_failed_url,
        ]);

        // Redirect to the checkout session URL
        return redirect()->away($checkoutSession->url);

    }
    public function stripe_success(Request $request) {
        $after_success_url = route('payment-success');
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
            session()->put('after_success_url', $after_success_url);
            session()->put('after_success_transaction', $session->payment_intent);
            session()->put('payment_details', $paymentDetails);

            return redirect($after_success_url);
        }

        $after_failed_url = session()->get('after_failed_url');
        return redirect($after_failed_url);
    }
    public function pay_via_razorpay(Request $request) {
        $payment_setting = $this->get_basic_payment_info();

        $after_success_url = route('payment-success');
        $after_failed_url = route('payment-failed');

        $razorpay_credentials = (object) [
            'razorpay_key'    => $payment_setting->razorpay_key,
            'razorpay_secret' => $payment_setting->razorpay_secret,
        ];

        return $this->pay_with_razorpay($request, $razorpay_credentials, $request->payable_amount, $after_success_url, $after_failed_url);

    }
    public function pay_with_razorpay(Request $request, $razorpay_credentials, $payable_amount, $after_success_url, $after_failed_url) {
        $input = $request->all();
        $api = new Api($razorpay_credentials->razorpay_key, $razorpay_credentials->razorpay_secret);
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        if (count($input) && !empty($input['razorpay_payment_id'])) {
            try {
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

                Session::put('after_success_url', $after_success_url);
                Session::put('after_failed_url', $after_failed_url);
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
        $payment_setting = $this->get_basic_payment_info();
        $curl = curl_init();
        $tnx_id = $request->tnx_id;
        $url = "https://api.flutterwave.com/v3/transactions/$tnx_id/verify";
        $token = $payment_setting?->flutterwave_secret_key;
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

            return response()->json(['message' => 'Payment Success.']);

        } else {
            $notification = __('Payment failed, please try again');
            return response()->json(['message' => $notification], 403);
        }

    }
    public function paystack_payment(Request $request) {
        $payment_setting = $this->get_basic_payment_info();

        $reference = $request->reference;
        $transaction = $request->tnx_id;
        $secret_key = $payment_setting?->paystack_secret_key;
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
            return response()->json(['message' => 'Payment Success.']);
        } else {
            $notification = __('Payment failed, please try again');
            return response()->json(['message' => $notification], 403);
        }
    }
    public function pay_via_mollie() {
        $after_success_url = route('payment-success');
        $after_failed_url = route('payment-failed');

        session()->put('after_success_url', $after_success_url);
        session()->put('after_failed_url', $after_failed_url);

        $payment_setting = $this->get_basic_payment_info();

        $mollie_credentials = (object) [
            'mollie_key' => $payment_setting->mollie_key,
        ];

        return $this->pay_with_mollie($mollie_credentials);
    }
    public function pay_with_mollie($mollie_credentials) {
        $payable_currency = session()->get('payable_currency');
        $paid_amount = session()->get('paid_amount');

        try {
            $mollie = new \Mollie\Api\MollieApiClient();
            $mollie->setApiKey($mollie_credentials->mollie_key);

            $payment = $mollie->payments->create([
                "amount"      => [
                    "currency" => "$payable_currency",
                    "value"    => "$paid_amount",
                ],
                "description" => userAuth()?->name,
                "redirectUrl" => route('mollie-payment-success'),
            ]);
            $payment = $mollie->payments->get($payment->id);

            session()->put('payment_id', $payment->id);
            session()->put('mollie_credentials', $mollie_credentials);

            return redirect($payment->getCheckoutUrl(), 303);

        } catch (Exception $ex) {
            info($ex->getMessage());
            return redirect()->route('client.order')->with(['message' => __('Payment failed, please try again'), 'alert-type' => 'error']);
        }

    }

    public function mollie_payment_success() {
        $mollie_credentials = Session::get('mollie_credentials');

        $mollie = new \Mollie\Api\MollieApiClient();
        $mollie->setApiKey($mollie_credentials->mollie_key);
        $payment = $mollie->payments->get(session()->get('payment_id'));

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
    public function pay_via_instamojo() {
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

        return $this->pay_with_instamojo($instamojo_credentials);
    }
    public function pay_with_instamojo($instamojo_credentials) {
        $payable_currency = session()->get('payable_currency');
        $paid_amount = session()->get('paid_amount');

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
                'amount'                  => $paid_amount,
                'phone'                   => '918160651749',
                'buyer_name'              => userAuth()?->name,
                'redirect_url'            => route('instamojo-success'),
                'send_email'              => true,
                'webhook'                 => 'http://www.example.com/webhook/',
                'send_sms'                => true,
                'email'                   => userAuth()?->email,
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
                return redirect()->route('client.order')->with(['message' => __('Payment failed, please try again'), 'alert-type' => 'error']);
            }

        } catch (Exception $ex) {
            info($ex->getMessage());
            return redirect()->route('client.order')->with(['message' => __('Payment failed, please try again'), 'alert-type' => 'error']);
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
                Session::put('paid_amount', $data->payment->amount);
                $after_success_url = Session::get('after_success_url');

                return redirect($after_success_url);
            }
        } else {
            $after_failed_url = Session::get('after_failed_url');

            return redirect($after_failed_url);
        }
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
                $user = userAuth();
                [$subject, $message] = $this->fetchEmailTemplate('approve_payment',['client_name' => $user->name,'orderId' => "#$order->order_id"]);
                $this->sendMail($user->email, $subject, $message);
            } catch (Exception $e) {
                info($e->getMessage());
            }


            $this->paymentService->removeSessions();

            return redirect()->route('client.order')->with(['message' => __('Payment Success.'), 'alert-type' => 'success']);
        } catch (Exception $e) {
            DB::rollback();
            info($e->getMessage());
            return redirect()->route('client.order')->with(['message' => __('Payment failed, please try again'), 'alert-type' => 'error']);
        }
    }

    public function payment_failed() {
        $this->paymentService->removeSessions();
        return redirect()->route('client.order')->with(['message' => __('Payment failed, please try again'), 'alert-type' => 'error']);
    }
}

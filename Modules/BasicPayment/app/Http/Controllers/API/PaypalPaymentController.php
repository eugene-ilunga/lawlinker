<?php

namespace Modules\BasicPayment\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ShoppingCart;
use App\Traits\GetGlobalInformationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalPaymentController extends Controller
{
    use GetGlobalInformationTrait;
    private $paymentService;
    function __construct() {
        $this->paymentService = app(\Modules\BasicPayment\app\Services\PaymentMethodService::class);
    }
    public function pay_via_paypal() {
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

        $after_success_url = route('payment-api.webview-success-payment',['bearer_token' => request()->bearer_token]);
        $after_failed_url = route('payment-api.webview-failed-payment');

        $paypal_credentials = (object) [
            'paypal_client_id'    => $basic_payment->paypal_client_id,
            'paypal_secret_key'   => $basic_payment->paypal_secret_key,
            'paypal_account_mode' => $basic_payment->paypal_account_mode,
        ];

        return $this->pay_with_paypal($paypal_credentials, $payable_amount, $currency_code, $after_success_url, $after_failed_url, $user);

    }
    public function pay_with_paypal($paypal_credentials, $payable_amount, $currency_code, $after_success_url, $after_failed_url, $user) {

        config(['paypal.mode' => $paypal_credentials->paypal_account_mode]);

        if ($paypal_credentials->paypal_account_mode == 'sandbox') {
            config(['paypal.sandbox.client_id' => $paypal_credentials->paypal_client_id]);
            config(['paypal.sandbox.client_secret' => $paypal_credentials->paypal_secret_key]);
        } else {
            config(['paypal.live.client_id' => $paypal_credentials->paypal_client_id]);
            config(['paypal.live.client_secret' => $paypal_credentials->paypal_secret_key]);
        }

        $calculate_payable_charge = $this->paymentService->getApiPayableAmount($this->paymentService::PAYPAL, $payable_amount, $currency_code);
        $payable_with_charge = $calculate_payable_charge?->payable_amount + $calculate_payable_charge?->gateway_charge;

        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));

            //paypal app id set
            $paypalToken = $provider->getAccessToken();
            $app_id = $paypalToken['app_id'];
            config(['paypal.live.app_id' => $app_id]);
            $paypal_credentials->paypal_app_id = $app_id;

            $response = $provider->createOrder([
                'intent'              => 'CAPTURE',
                'application_context' => [
                    'return_url' => route('payment-api.paypal-success',['bearer_token' => request()->bearer_token]),
                    'cancel_url' => $after_failed_url,
                ],
                'purchase_units'      => [
                    0 => [
                        'amount' => [
                            'currency_code' => $currency_code,
                            'value'         => $payable_with_charge,
                        ],
                    ],
                ],
            ]);
        } catch (\Exception $ex) {
            info($ex->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => __('Payment failed, please try again'),
            ], 500);
        }

        if (isset($response['id']) && $response['id'] != null) {

            Session::put('after_success_url', $after_success_url);
            Session::put('after_failed_url', $after_failed_url);
            Session::put('paypal_credentials', $paypal_credentials);

            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }

            return response()->json([
                'status'  => 'error',
                'message' => __('Payment failed, please try again'),
            ], 400);

        } else {
            return response()->json([
                'status'  => 'error',
                'message' => __('Payment failed, please try again'),
            ], 400);
        }

    }

    public function paypal_success(Request $request) {
        $paypal_credentials = Session::get('paypal_credentials');
        config(['paypal.mode' => $paypal_credentials->paypal_account_mode]);

        if ($paypal_credentials->paypal_account_mode == 'sandbox') {
            config(['paypal.sandbox.client_id' => $paypal_credentials->paypal_client_id]);
            config(['paypal.sandbox.client_secret' => $paypal_credentials->paypal_secret_key]);
        } else {
            config(['paypal.live.client_id' => $paypal_credentials->paypal_client_id]);
            config(['paypal.live.client_secret' => $paypal_credentials->paypal_secret_key]);
            config(['paypal.live.app_id' => $paypal_credentials->paypal_app_id]);
        }
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            Session::put('after_success_transaction', $request->PayerID);
            $after_success_url = Session::get('after_success_url');
            $paid_amount = $this->checkArrayIsset($response['purchase_units'][0]['payments']['captures'][0]['amount']['value']);
            Session::put('paid_amount', $paid_amount);

            $details = [
                'payments_captures_id' => $this->checkArrayIsset($response['purchase_units'][0]['payments']['captures'][0]['id']),
                'amount'               => $this->checkArrayIsset($response['purchase_units'][0]['payments']['captures'][0]['amount']['value']),
                'currency'             => $this->checkArrayIsset($response['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code']),
                'paid'                 => $this->checkArrayIsset($response['purchase_units'][0]['payments']['captures'][0]['seller_receivable_breakdown']['gross_amount']['value']),
                'paypal_fee'           => $this->checkArrayIsset($response['purchase_units'][0]['payments']['captures'][0]['seller_receivable_breakdown']['paypal_fee']['value']),
                'net_amount'           => $this->checkArrayIsset($response['purchase_units'][0]['payments']['captures'][0]['seller_receivable_breakdown']['net_amount']['value']),
                'status'               => $this->checkArrayIsset($response['purchase_units'][0]['payments']['captures'][0]['status']),
            ];
            Session::put('payment_details', $details);
            return redirect($after_success_url);

        } else {
            $after_failed_url = Session::get('after_failed_url');
            return redirect($after_failed_url);
        }

    }
    private function checkArrayIsset($value) {
        return isset($value) ? $value : null;
    }
}

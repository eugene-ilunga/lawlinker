<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Modules\BasicPayment\app\Models\BasicPayment;
use Modules\BasicPayment\app\Services\PaymentMethodService;

trait GetGlobalInformationTrait {
    // get basic payment gateway information
    public function get_basic_payment_info() {
        $basic_payment = Cache::rememberForever('basic_payment', function () {

            $payment_info = BasicPayment::get();

            $basic_payment = [];
            foreach ($payment_info as $payment_item) {
                $basic_payment[$payment_item->key] = $payment_item->value;
            }

            return (object) $basic_payment;
        });

        return $basic_payment;

    }
    /**
     * @param $currency_code
     */
    private function getMultiCurrencyInfo($currency_code = null) {
        if(is_null($currency_code)){
            $currency_code = getSessionCurrency();
        }
        $gateway_currency = allCurrencies()->where('currency_code', $currency_code)->first();

        return [
            'currency_code' => $gateway_currency->currency_code,
            'country_code'  => $gateway_currency->country_code,
            'currency_rate' => $gateway_currency->currency_rate,
            'currency_id'   => $gateway_currency->id,
        ];
    }

    private function getCurrencyDetails() {
        $gateway_currency = allCurrencies()->where('is_default', 'yes')->first();

        return [
            'currency_code' => $gateway_currency->currency_code,
            'country_code'  => $gateway_currency->country_code,
            'currency_rate' => $gateway_currency->currency_rate,
        ];
    }

    // calculate amount for all payment method
    /**
     * @param $payable_amount
     * @param $gateway_name
     */
    public function calculate_payable_charge($payable_amount, $gateway_name,$currency_code = null) {
        $paymentService = app(PaymentMethodService::class);

        $paymentDetails = $paymentService->getGatewayDetails($gateway_name);

        $gateway_charge = $paymentDetails->charge;

        $multiCurrencyInfo = $this->getMultiCurrencyInfo($currency_code);
        $currency_code = $multiCurrencyInfo['currency_code'];
        $country_code = $multiCurrencyInfo['country_code'];
        $currency_rate = $multiCurrencyInfo['currency_rate'];
        $currency_id = $multiCurrencyInfo['currency_id'];

        $payable_amount = $payable_amount * $currency_rate;
        $gateway_charge = $payable_amount * ($gateway_charge / 100);

        $payable_with_charge = $payable_amount + $gateway_charge;
        $payable_with_charge = sprintf('%0.2f', $payable_with_charge);
        
        session()->put('payable_with_charge', $payable_with_charge);
        session()->put('gateway_charge', $gateway_charge);
        session()->put('payable_currency', $currency_code);

        return (object) [
            'country_code'               => $country_code,
            'currency_code'              => $currency_code,
            'currency_id'                => $currency_id ?? 1,
            'payable_amount'             => $payable_amount,
            'gateway_charge'             => $gateway_charge,
            'payable_with_charge'        => $payable_with_charge,
        ];
    }
    // calculate amount for all payment method
    public function calculate_api_payable_charge($payable_amount, $gateway_name, $payable_currency) {
        $paymentService = app(PaymentMethodService::class);

        $paymentDetails = $paymentService->getGatewayDetails($gateway_name);

        $gateway_charge = $paymentDetails->charge;

        $multiCurrencyInfo = $this->getMultiCurrencyInfo($payable_currency);
        $currency_code = $multiCurrencyInfo['currency_code'];
        $country_code = $multiCurrencyInfo['country_code'];
        $currency_rate = $multiCurrencyInfo['currency_rate'];
        $currency_id = $multiCurrencyInfo['currency_id'];

        $payable_amount = $payable_amount * $currency_rate;
        $gateway_charge = $payable_amount * ($gateway_charge / 100);

        $payable_with_charge = $payable_amount + $gateway_charge;
        $payable_with_charge = sprintf('%0.2f', $payable_with_charge);

        $paid_amount = $payable_amount + $gateway_charge;
        
        session()->put('paid_amount', $paid_amount);
        session()->put('payable_with_charge', $payable_with_charge);
        session()->put('gateway_charge', $gateway_charge);
        session()->put('payable_currency', $currency_code);

        return (object) [
            'country_code'               => $country_code,
            'currency_code'              => $currency_code,
            'currency_id'                => $currency_id,
            'payable_amount'             => $payable_amount,
            'gateway_charge'             => $gateway_charge,
            'payable_with_charge'        => $payable_with_charge,
        ];
    }
}

<?php

namespace Modules\BasicPayment\database\seeders;

use Illuminate\Database\Seeder;
use Modules\BasicPayment\app\Models\BasicPayment;
use Modules\Currency\app\Models\MultiCurrency;

class BasicPaymentInfoSeeder extends Seeder
{
    public function run(): void
    {
        $basic_payment_info = [
            'stripe_key' => 'stripe_key',
            'stripe_secret' => 'stripe_secret',
            'stripe_currency_id' => 1,
            'stripe_status' => 'inactive',
            'stripe_charge' => 0.00,
            'stripe_image' => 'uploads/website-images/stripe.png',
            'paypal_client_id' => 'paypal_client_id',
            'paypal_secret_key' => 'paypal_secret_key',
            'paypal_account_mode' => 'sandbox',
            'paypal_currency_id' =>1,
            'paypal_charge' => 0.00,
            'paypal_status' => 'inactive',
            'paypal_image' => 'uploads/website-images/paypal.png',
            'bank_information' => "Bank Name => Your bank name\r\nAccount Number =>  Your bank account number\r\nRouting Number => Your bank routing number\r\nBranch => Your bank branch name",
            'bank_status' => 'active',
            'bank_image' => 'uploads/website-images/bank-pay.png',
            'bank_charge' => 0.00,
            'bank_currency_id' => 1,
            'razorpay_key' => 'razorpay_key',
            'razorpay_secret' => 'razorpay_secret',
            'razorpay_name' => 'LawMent',
            'razorpay_description' => 'This is test payment window',
            'razorpay_charge' => 0.00,
            'razorpay_theme_color' => '#6d0ce4',
            'razorpay_status' => 'inactive',
            'razorpay_currency_id' => 1,
            'razorpay_image' => 'uploads/website-images/razorpay.png',
            'flutterwave_public_key' => 'flutterwave_public_key',
            'flutterwave_secret_key' => 'flutterwave_secret_key',
            'flutterwave_app_name' => 'LawMent',
            'flutterwave_charge' => 0.00,
            'flutterwave_currency_id' => 1,
            'flutterwave_status' => 'inactive',
            'flutterwave_image' => 'uploads/website-images/flutterwave.png',
            'paystack_public_key' => 'paystack_public_key',
            'paystack_secret_key' => 'paystack_secret_key',
            'paystack_status' => 'inactive',
            'paystack_charge' => 0.00,
            'paystack_image' => 'uploads/website-images/paystack.png',
            'paystack_currency_id' => 1,
            'mollie_key' => 'mollie_key',
            'mollie_charge' => 0.00,
            'mollie_image' => 'uploads/website-images/mollie.png',
            'mollie_status' => 'inactive',
            'mollie_currency_id' => 1,
            'instamojo_account_mode' => 'Sandbox',
            'instamojo_api_key' => 'instamojo_api_key',
            'instamojo_auth_token' => 'instamojo_auth_token',
            'instamojo_charge' => 0.00,
            'instamojo_image' => 'uploads/website-images/instamojo.png',
            'instamojo_currency_id' => 1,
            'instamojo_status' => 'inactive',
            'freshpay_merchant_id' => 'j&/v<Gj6.C;,,;g`b',
            'freshpay_merchant_secret' => 'jzS1klPr#cmJ5Lj9Hb',
            'freshpay_api_url' => 'https://paydrc.gofreshbakery.net/api/v5/',
            'freshpay_firstname' => 'Fresh',
            'freshpay_lastname' => 'Pay',
            'freshpay_email' => 'support@example.com',
            'freshpay_callback_signature_secret' => '',
            'freshpay_callback_aes_key' => '',
            'freshpay_callback_aes_iv' => '',
            'freshpay_charge' => 0.00,
            'freshpay_image' => 'uploads/website-images/freshpay.png',
            'freshpay_currency_id' => 1,
            'freshpay_status' => 'inactive',
        ];

        foreach ($basic_payment_info as $index => $payment_item) {
            $new_item = new BasicPayment();
            $new_item->key = $index;
            $new_item->value = $payment_item;
            $new_item->save();
        }
    }
}

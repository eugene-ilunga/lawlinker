<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'freshpay' => [
        'url' => env('FRESHPAY_API_URL', 'https://paydrc.gofreshbakery.net/api/v5/'),
        'merchant_id' => env('FRESHPAY_MERCHANT_ID'),
        'merchant_secret' => env('FRESHPAY_MERCHANT_SECRET'),
        'callback_signature_secret' => env('FRESHPAY_CALLBACK_SIGNATURE_SECRET'),
        'callback_aes_key' => env('FRESHPAY_CALLBACK_AES_KEY'),
        'callback_aes_iv' => env('FRESHPAY_CALLBACK_AES_IV'),
        'callback_whitelist' => env('FRESHPAY_CALLBACK_WHITELIST', ''),
    ],

];

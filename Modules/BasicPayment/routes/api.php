<?php

use Illuminate\Support\Facades\Route;
use Modules\BasicPayment\app\Http\Controllers\API\PaymentController;

Route::middleware('auth:api')->group(function () {
    Route::get('payment-gateway-list', [PaymentController::class, 'all_payment']);
    Route::get('support-currency/{method}',[PaymentController::class, 'support_currency']);
    Route::get('bank-details', [PaymentController::class, 'bank_information']);
    Route::post('pay-via-bank',[PaymentController::class, 'pay_via_bank']);
});

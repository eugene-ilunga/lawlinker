<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\app\Http\Controllers\OrderController;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'translation']], function () {
    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index')->name('orders');
        Route::get('/active-orders', 'active_orders')->name('active-orders');
        Route::get('/pending-orders', 'pending_order')->name('pending-orders');
        Route::get('/order/{id}', 'show')->name('order');
        Route::post('/order-payment-approved/{id}', 'order_payment_approved')->name('order-payment-approved');
        Route::delete('/order-delete/{id}', 'destroy')->name('order-delete');
    });
});

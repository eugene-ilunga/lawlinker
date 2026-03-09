<?php

use Illuminate\Support\Facades\Route;
use Modules\ContactMessage\app\Http\Controllers\Admin\ContactInfoController;
use Modules\ContactMessage\app\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use Modules\ContactMessage\app\Http\Controllers\ContactMessageController;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'translation']], function () {
    Route::get('contact-messages', [AdminContactMessageController::class, 'index'])->name('contact-messages');
    Route::get('contact-message/{id}', [AdminContactMessageController::class, 'show'])->name('contact-message');
    Route::delete('contact-message-delete/{id}', [AdminContactMessageController::class, 'destroy'])->name('contact-message-delete');

    Route::get('contact-info', [ContactInfoController::class, 'index'])->name('contact-info');
    Route::put('contact-info', [ContactInfoController::class, 'update'])->name('contact-info.update');
    Route::get('invoice-contact', [ContactInfoController::class, 'prescription_contact'])->name('prescription-contact');
});

Route::post('send-contact-message', [ContactMessageController::class, 'store'])->name('send-contact-message');

<?php

use Illuminate\Support\Facades\Route;
use Modules\Appointment\app\Http\Controllers\AppointmentController;
use Modules\Appointment\app\Http\Controllers\PrescribeController;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'translation']], function () {
    Route::controller(AppointmentController::class)->group(function () {
        Route::get('appointment', 'index')->name('appointment.index');
        Route::get('new-appointments', 'new_appointment')->name('appointment.new');
        Route::get('pending-appointments', 'pending_appointment')->name('appointment.pending');
        Route::get('appointment/{id}', 'show')->name('appointment.show');
        Route::get('payment-history', 'paymentHistory')->name('payment.history');
        Route::get('upcomming-meeting', 'upcommingMeeting')->name('upcomming-meeting');
        Route::get('previous-meeting', 'previousMeeting')->name('previous-meeting');
    });

    Route::controller(PrescribeController::class)->group(function () {
        Route::get('consultation-notes', 'index')->name('prescribe');
        Route::get('consultation-notes-show/{id}', 'show')->name('prescribe.show');
        Route::get('consultation-notes/{id}', 'printPrescription')->name('print.prescription');
    });
});
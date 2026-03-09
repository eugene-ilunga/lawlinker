<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lawyer\ProfileController;
use App\Http\Controllers\Lawyer\DashboardController;
use App\Http\Controllers\Lawyer\LawyerMeetingController;
use App\Http\Controllers\Lawyer\LawyerMessageController;
use App\Http\Controllers\Lawyer\LawyerWithdrawController;
use App\Http\Controllers\Lawyer\ZoomCredentialController;
use App\Http\Controllers\Lawyer\Auth\RegisteredController;
use App\Http\Controllers\Lawyer\Auth\NewPasswordController;
use App\Http\Controllers\Lawyer\LawyerAppointmentController;
use App\Http\Controllers\Lawyer\LawyerSocialMediaController;
use App\Http\Controllers\Lawyer\Auth\PasswordResetLinkController;
use App\Http\Controllers\Lawyer\Auth\AuthenticatedSessionController;

Route::get('lawyers/login', fn() => to_route('login',['type'=>'lawyer']));

Route::group(['as' => 'lawyer.', 'prefix' => 'lawyer', 'middleware' => ['maintenance.mode', 'translation']], function () {
    Route::post('register', [RegisteredController::class, 'store'])->name('register');
    Route::get('verification/{token}', [RegisteredController::class, 'custom_lawyer_verification'])->name('verification');

    Route::post('lawyer-login', [AuthenticatedSessionController::class, 'store'])->name('login');
    Route::post('forget-password', [PasswordResetLinkController::class, 'custom_forget_password'])->name('forget-password');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'custom_reset_password_page'])->name('password.reset');
    Route::post('/reset-password-store/{token}', [NewPasswordController::class, 'custom_reset_password_store'])->name('password.reset-store');

    Route::middleware(['auth:lawyer'])->group(function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::controller(ProfileController::class)->group(function () {
            Route::get('edit-profile', 'edit_profile')->name('edit-profile');
            Route::put('profile-update', 'profile_update')->name('profile-update');
            Route::get('update-password', 'change_password')->name('change-password');
            Route::put('update-password', 'update_password')->name('update-password');
        });
        Route::controller(LawyerAppointmentController::class)->group(function () {
            Route::get('today-appointment', 'todayAppointment')->name('today.appointment');
            Route::get('new-appointment', 'newAppointment')->name('new.appointment');
            Route::get('all-appointment', 'allAppointment')->name('all.appointment');
            Route::get('not-consulted', 'notTreatedAppointments')->name('not.treated.appointment');
            Route::get('old-appointment/{id}', 'oldAppointment')->name('old.appointment');
            Route::get('consultation/{id}', 'treatment')->name('treatment');
            Route::post('consultation-store/{id}', 'storeTreatment')->name('treatment.store');
            Route::get('consultation-edit/{id}', 'editTreatment')->name('treatment.edit');
            Route::post('consultation-update/{id}', 'updateTreatment')->name('treatment.update');
            Route::get('already-consulted/{id}', 'allReadyTreatment')->name('already.treatment');
            Route::get('consultation-notes/{id}', 'printPrescription')->name('print.prescription');
            Route::get('download-document/{id}/{path}', 'downloadDocument')->name('download.document');
            Route::delete('delete-document/{appointment_id}/{id}', 'deleteDocument')->name('delete.document');
            Route::get('payment-history', 'paymentHistory')->name('payment.history');
            Route::get('schedule', 'schedule')->name('schedule');
        });
        Route::controller(LawyerMessageController::class)->group(function () {
            Route::get('message', 'index')->name('message.index');
            Route::get('message-box/{id}', 'messagebox')->name('message.box');
            Route::get('get-message/{id}', 'getmessage')->name('get.message');
            Route::get('seen-message/{id}', 'seenMessage')->name('seen.message');
            Route::post('send-message', 'sendmessage')->name('send.message');
        });
        Route::resource('withdraw', LawyerWithdrawController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('get-withdraw-account-info/{id}', [LawyerWithdrawController::class, 'getWithDrawAccountInfo'])->name('get-withdraw-account-info');

        Route::controller(ZoomCredentialController::class)->group(function () {
            Route::get('zoom-credential', 'index')->name('zoom-credential');
            Route::post('zoom-credential', 'store')->name('zoom-credential.store');
        });

        Route::controller(LawyerMeetingController::class)->group(function () {
            Route::get('/zoom-meetings', 'index')->name('zoom-meetings');
            Route::get('/create-zoom-meeting', 'create')->name('create-zoom-meeting');
            Route::post('/store-zoom-meeting', 'store')->name('store-zoom-meeting');
            Route::get('/edit-zoom-meeting/{id}', 'edit')->name('edit-zoom-meeting');
            Route::post('/update-zoom-meeting/{id}', 'update')->name('update-zoom-meeting');
            Route::delete('/delete-zoom-meeting/{id}', 'destroy')->name('delete-zoom-meeting');
            
            Route::get('/meeting-history', 'meetingHistory')->name('meeting-history');
            Route::get('/upcomming-meeting', 'upCommingMeeting')->name('upcomming-meeting');

        });

        Route::get('social-link', [LawyerSocialMediaController::class, 'index'])->name('social-link.index');
        Route::post('social-link', [LawyerSocialMediaController::class, 'store'])->name('social-link.store');
        Route::post('social-link/{id}', [LawyerSocialMediaController::class, 'update'])->name('social-link.update');
        Route::delete('social-link/{id}', [LawyerSocialMediaController::class, 'destroy'])->name('social-link.destroy');
        Route::put('social-link/status-update/{id}', [LawyerSocialMediaController::class, 'statusUpdate'])->name('social-link.status-update');
    });

});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Client\MeetingController;
use App\Http\Controllers\Client\MessageController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

Route::middleware(['guest:web', 'maintenance.mode', 'translation'])->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store'])->name('client-register');
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('client-login', [AuthenticatedSessionController::class, 'store'])->name('client-login');
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forget-password', [PasswordResetLinkController::class, 'custom_forget_password'])->name('forget-password');
    Route::get('/reset-password-page/{token}', [NewPasswordController::class, 'custom_reset_password_page'])->name('reset-password-page');
    Route::post('/reset-password-store/{token}', [NewPasswordController::class, 'custom_reset_password_store'])->name('reset-password-store');
    Route::get('/client-verification/{token}', [RegisteredUserController::class, 'custom_user_verification'])->name('user-verification');
    Route::controller(SocialiteController::class)->group(function () {
        Route::get('auth/{driver}', 'redirectToDriver')->name('auth.social');
        Route::get('auth/{driver}/callback', 'handleDriverCallback')->name('auth.social.callback');
    });
});

Route::middleware(['auth:web', 'translation', 'maintenance.mode'])->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('dashboard', [ProfileController::class, 'dashboard'])->middleware('verified')->name('dashboard');

    Route::group(['as' => 'client.', 'prefix' => 'client'], function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::get('dashboard', fn() => to_route('dashboard'));
            Route::post('update-profile', 'updateProfile')->name('update.profile');
            Route::get('appointment', 'appointments')->name('appointment');
            Route::get('show/{id}/appointment', 'showAppointment')->name('show.appointment');
             Route::get('download-document/{id}/{path}', 'downloadDocument')->name('download.document');
            Route::get('order', 'orders')->name('order');
            Route::get('change-password', 'changePassword')->name('change.password');
            Route::post('store-change-password', 'storePassword')->name('update.password');
            Route::get('consultation-notes/{id}', 'printPrescription')->name('print.prescription');
        });

        Route::controller(MessageController::class)->group(function () {
            Route::get('message', 'index')->name('message');
            Route::get('message-box/{slug}', 'messagebox')->name('message.box');
            Route::get('get-message/{id}', 'getmessage')->name('get.message');
            Route::get('seen-message/{id}', 'seenMessage')->name('seen.message');
            Route::post('send-message', 'sendmessage')->name('send.message');
        });

        Route::controller(MeetingController::class)->group(function () {
            Route::get('meeting-history', 'meetingHistory')->name('meeting-history');
            Route::get('upcomming-meeting', 'upCommingMeeting')->name('upcomming-meeting');
        });

        Route::get('payment', [PaymentController::class,'payment'])->name('payment');
    });

});
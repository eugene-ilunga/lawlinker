<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\API\MenuController;
use App\Http\Controllers\API\AllPagesController;
use App\Http\Controllers\API\FrontendController;
use App\Http\Controllers\API\AppointmentController;
use App\Http\Controllers\API\Lawyer\LeaveController;
use App\Http\Controllers\API\Client\MessageController;
use App\Http\Controllers\API\Client\ProfileController;
use App\Http\Controllers\API\Lawyer\LawyerMeetingController;
use App\Http\Controllers\API\Lawyer\LawyerWithdrawController;
use App\Http\Controllers\API\Lawyer\ZoomCredentialController;
use App\Http\Controllers\API\Client\AuthenticatedController;
use App\Http\Controllers\API\Lawyer\LawyerAppointmentController;
use App\Http\Controllers\API\Lawyer\LawyerSocialMediaController;
use App\Http\Controllers\API\Lawyer\MessageController as LawyerMessageController;
use App\Http\Controllers\API\Lawyer\ProfileController as LawyerProfileController;
use App\Http\Controllers\API\Lawyer\AuthenticatedController as LawyerAuthenticatedController;


Broadcast::routes(['middleware' => ['auth:sanctum']]);
Route::prefix('client')->group(function () {
    Route::middleware(['guest:sanctum'])->group(function () {
        Route::post('register', [AuthenticatedController::class, 'register'])->name('api.client-register');
        Route::post('login', [AuthenticatedController::class, 'login'])->name('api.client-login');
        Route::post('forget-password', [AuthenticatedController::class, 'forgetPassword'])->name('api.client-forget-password');
        Route::post('reset-password', [AuthenticatedController::class, 'resetPassword'])->name('api.client-reset-password');
    });
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthenticatedController::class, 'logout'])->name('api.client-logout');
        Route::post('logout/all-app', [AuthenticatedController::class, 'logoutAllApp'])->name('api.client-logoutAllApp');

        Route::controller(ProfileController::class)->group(function () {
            Route::get('dashboard', 'dashboard');
            Route::get('profile', 'account');
            Route::post('profile-update', 'updateProfile');
            Route::put('password-update', 'updatePassword');
            Route::get('appointments', 'appointments');
            Route::get('appointments/{id}', 'showAppointment');
            Route::get('orders', 'orders');
            Route::get('orders/{id}', 'showOrder');
            Route::get('meeting-history', 'meetingHistory');
            Route::get('upcoming-meeting', 'upComingMeeting');
        });
        Route::controller(MessageController::class)->group(function () {
            Route::get('appointed-lawyers', 'index');
            Route::get('lawyer-message/{lawyer_id}', 'getMessage');
            Route::post('send-message', 'sendMessage');
            Route::get('seen-message/{lawyer_id}', 'seenMessage');
        });
    });
});

Route::prefix('lawyer')->group(function () {
    Route::middleware(['guest:lawyer_api'])->group(function () {
        Route::post('register', [LawyerAuthenticatedController::class, 'register'])->name('api.lawyer-register');
        Route::post('login', [LawyerAuthenticatedController::class, 'login'])->name('api.lawyer-login');
        Route::post('forget-password', [LawyerAuthenticatedController::class, 'forgetPassword'])->name('api.lawyer-forget-password');
        Route::post('reset-password', [LawyerAuthenticatedController::class, 'resetPassword'])->name('api.lawyer-reset-password');
    });
    Route::middleware('auth:lawyer_api')->group(function () {
        Route::post('logout', [LawyerAuthenticatedController::class, 'logout'])->name('api.lawyer-logout');
        Route::post('logout/all-app', [LawyerAuthenticatedController::class, 'logoutAllApp'])->name('api.lawyer-logoutAllApp');

        Route::controller(LawyerProfileController::class)->group(function () {
            Route::get('dashboard', 'dashboard');
            Route::get('profile', 'account');
            Route::post('profile-update', 'updateProfile');
            Route::put('password-update', 'updatePassword');
        });

        Route::controller(LawyerWithdrawController::class)->group(function () {
            Route::get('withdraw-methods', 'withdrawMethods');
            Route::get('withdraw-method-info/{id}', 'getWithDrawMethodInfo');
            Route::get('withdraw-summary', 'withdrawSummary');
            Route::get('withdraw-list', 'withdrawList');
            Route::post('withdraw-request', 'store');
            Route::get('withdraw-request/{id}', 'showWithdraw');
        });

        Route::controller(LawyerAppointmentController::class)->group(function () {
            Route::get('days', 'days');
            Route::get('today-appointment', 'todayAppointment');
            Route::get('new-appointment', 'newAppointment');
            Route::get('all-appointment', 'allAppointment');
            Route::get('old-appointment/{client_id}', 'clientOldAppointment');
            Route::post('consultation/{appointment_id}', 'treatment');
            Route::post('update-consultation/{appointment_id}', 'updateTreatment');
            Route::get('consultation-documents/{appointment_id}', 'appointmentDocuments');
            Route::delete('delete-consultation-document/{appointment_id}/{document_id}', 'deleteDocument');
            Route::get('download-consultation-document/{appointment_id}/{path}', 'downloadDocument');
            Route::get('payment-history', 'paymentHistory');
            Route::get('schedules', 'schedule');
        });

        Route::controller(LeaveController::class)->group(function () {
            Route::get('leave', 'index');
            Route::post('leave', 'store');
            Route::put('leave/{id}', 'update');
            Route::delete('leave/{id}', 'destroy');
        });
        Route::controller(LawyerSocialMediaController::class)->group(function () {
            Route::get('social-link', 'index');
            Route::post('social-link', 'store');
            Route::post('social-link/{id}', 'update');
            Route::put('social-link/status-update/{id}', 'statusUpdate');
            Route::delete('social-link/{id}', 'destroy');
        });

        Route::controller(ZoomCredentialController::class)->group(function () {
            Route::get('zoom-credential', 'index');
            Route::post('zoom-credential', 'store');
        });

        Route::controller(LawyerMeetingController::class)->group(function () {
            Route::get('zoom-meeting', 'index');
            Route::get('meeting-history', 'meetingHistory');
            Route::get('upcoming-meeting', 'upComingMeeting');
            Route::delete('zoom-meeting/{id}', 'destroy');

            Route::get('appointed-clients', 'users');
            Route::post('store-zoom-meeting', 'store');
            Route::get('single-zoom-meeting/{id}', 'edit');
            Route::post('update-zoom-meeting/{id}', 'update');
        });

        Route::controller(LawyerMessageController::class)->group(function () {
            Route::get('appointed-clients', 'index');
            Route::get('client-message/{client_id}', 'getMessage');
            Route::post('send-message', 'sendMessage');
            Route::get('seen-message/{client_id}', 'seenMessage');
        });
    });
});

Route::controller(FrontendController::class)->group(function () {
    Route::get('settings', 'settings');
    Route::get('get-departments', 'departments');
    Route::get('get-locations', 'locations');
    Route::get('get-lawyers', 'lawyers');
    Route::get('get-services', 'services');
    Route::get('social-links', 'socialLinks');
    Route::get('contact-info', 'contactInfo');
    Route::get('language-list', 'allLanguages');
    Route::get('currency-list', 'allCurrency');
    Route::get('partners', 'partners');
    Route::get('seo-data', 'seoSetting');
    Route::get('subscriber-content', 'subscriberContent');
    Route::get('footer-recent-post', 'footerLatestNews');
    Route::post('contact-us', 'contactUs')->middleware('throttle:3,60');
    Route::post('subscribe-us', 'newsletter_request')->middleware('throttle:3,60');
    Route::get('get-language/{code?}', 'getLanguageFile');
    Route::get('on-boarding-screen', 'boardingScreen');
});
Route::controller(MenuController::class)->group(function () {
    Route::get('main-menu', 'mainMenu');
    Route::get('footer-menu-one', 'footerMenuOne');
    Route::get('footer-menu-two', 'footerMenuTwo');
});
Route::controller(AppointmentController::class)->group(function () {
    Route::get('get-department-lawyer/{department_id}', 'getDepartmentLawyer');
    Route::get('get-lawyer-schedule', 'getAppointmentSchedule');

    Route::middleware('auth:api')->group(function () {
        Route::post('create-appointment', 'createAppointment');
        Route::get('cart-list', 'totalAppointment');
        Route::delete('cart-remove/{id}', 'removeAppointment');
    });

    Route::get('client/consultation-documents/{appointment_id}', 'appointmentDocuments');
    Route::get('client/download-consultation-document/{appointment_id}/{path}', 'downloadDocument');
});
Route::controller(AllPagesController::class)->group(function () {
    Route::get('home', 'index');
    Route::get('about', 'aboutUs');
    Route::get('services', 'service');
    Route::get('services/{slug}', 'serviceDetails');
    Route::get('departments', 'department');
    Route::get('departments/{slug}', 'departmentDetails');
    Route::get('lawyers', 'lawyers');
    Route::get('lawyers/{slug}', 'lawyerDetails');
    Route::get('blogs', 'blogs');
    Route::get('blogs/{slug}', 'blogDetails');
    Route::get('category-blogs/{category_slug}', 'blogCategory');
    Route::get('testimonial', 'testimonial');
    Route::get('faq', 'faq');
    Route::get('privacy-policy', 'privacyPolicy');
    Route::get('terms-condition', 'termsCondition');
    Route::get('custom-pages', 'customPages');
});

//print prescription
Route::middleware('payment.api')->group(function () {
    Route::get('client/print/consultation-notes/{appointment_id}',[ProfileController::class,'printPrescription'] );
    Route::get('lawyer/print/consultation-notes/{appointment_id}', [LawyerAppointmentController::class,'printPrescription']);
});

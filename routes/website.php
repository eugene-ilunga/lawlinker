<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Client\AppointmentController;
use App\Http\Controllers\Client\BlogController;
use Illuminate\Support\Facades\Route;

Route::middleware(['translation', 'maintenance.mode'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('home', fn() => to_route('home'));

    Route::name('website.')->group(function () {
        Route::view('contact-us', 'client.contact-us');
        Route::controller(HomeController::class)->group(function () {
            Route::get('/about-us', 'aboutUs');
            Route::get('/service', 'service')->name('services');
            Route::get('service-details/{slug}', 'serviceDetails')->name('service.details');
            Route::get('/department', 'department')->name('departments');
            Route::get('/department-details/{slug}', 'departmentDetails')->name('department.details');
            Route::get('lawyers', 'lawyers')->name('lawyers');
            Route::get('lawyer-details/{slug}', 'lawyerDetails')->name('lawyer.details');
            Route::get('/search-lawyer', 'searchLawyer')->name('search.lawyer');
            Route::get('/testimonial', 'testimonial')->name('testimonial');
            Route::get('/faq', 'faq')->name('faq');
            Route::get('privacy-policy', 'privacyPolicy')->name('privacy-policy');
            Route::get('terms-condition', 'termsCondition')->name('termsCondition');
        });
        Route::controller(BlogController::class)->group(function () {
            Route::get('/blog', 'blog')->name('blogs');
            Route::get('/blog-details/{slug}', 'blogDetails')->name('blog.details');
            Route::get('/blog-category/{slug}', 'blogCategory')->name('blog.category');
            Route::post('comment-store/{slug}', 'commentStore')->name('comment.store');
        });

        Route::controller(AppointmentController::class)->group(function () {
            // ajax request for appointment
            Route::get('get-appointment/', 'getAppointment');
            Route::get('get-department-lawyer/{id}', 'getDepartmentLawyer');

            //appointment add to cart
            Route::post('create-appointment', 'createAppointment')->name('create.appointment');
            Route::get('remove-appointment/{id}', 'removeAppointment');
        });

        Route::get('page/{slug}', [HomeController::class, 'customPage'])->name('page');
    });
});
<?php

use Illuminate\Support\Facades\Route;
use Modules\Service\app\Http\Controllers\ServiceController;
use Modules\Service\app\Http\Controllers\ServiceFaqController;
use Modules\Service\app\Http\Controllers\ServiceUtilityController;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'translation']], function () {
    Route::resource('service', ServiceController::class)->names('service')->except('show');
    Route::put('/service/status-update/{id}', [ServiceController::class, 'statusUpdate'])->name('service.status-update');

    Route::controller(ServiceUtilityController::class)->group(function () {
        Route::get('service-gallery/{id}', 'showGallery')->name('service.gallery');
        Route::put('service-gallery/{id}', 'updateGallery')->name('service.gallery.update');
        Route::delete('service-gallery/{id}', 'deleteGallery')->name('service.gallery.delete');

        Route::get('service-videos/{id}', 'showVideos')->name('service.videos');
        Route::put('service-videos/{id}', 'updateVideos')->name('service.videos.update');
        Route::delete('service-videos/{id}', 'deleteVideos')->name('service.videos.delete');
    });

    Route::get('faq-by-service/{slug}', [ServiceFaqController::class, 'index'])->name('faq.by.service');
    Route::resource('service-faq', ServiceFaqController::class)->names('service-faq')->only(['store', 'update', 'destroy']);
    Route::put('service-faq/status-update/{id}', [ServiceFaqController::class, 'statusUpdate'])->name('service-faq.status-update');
});

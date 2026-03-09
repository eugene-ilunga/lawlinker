<?php

use Illuminate\Support\Facades\Route;
use Modules\Lawyer\app\Http\Controllers\LeaveController;
use Modules\Lawyer\app\Http\Controllers\LawyerController;
use Modules\Lawyer\app\Http\Controllers\LocationController;
use Modules\Lawyer\app\Http\Controllers\DepartmentController;
use Modules\Lawyer\app\Http\Controllers\DepartmentFaqController;
use Modules\Lawyer\app\Http\Controllers\DepartmentUtilityController;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'translation']], function () {
    Route::resource('department', DepartmentController::class)->names('department')->except('show');
    Route::put('/department/status-update/{id}', [DepartmentController::class, 'statusUpdate'])->name('department.status-update');

    Route::controller(DepartmentUtilityController::class)->group(function () {
        Route::get('department-gallery/{id}', 'showGallery')->name('department.gallery');
        Route::put('department-gallery/{id}', 'updateGallery')->name('department.gallery.update');
        Route::delete('department-gallery/{id}', 'deleteGallery')->name('department.gallery.delete');

        Route::get('department-videos/{id}', 'showVideos')->name('department.videos');
        Route::put('department-videos/{id}', 'updateVideos')->name('department.videos.update');
        Route::delete('department-videos/{id}', 'deleteVideos')->name('department.videos.delete');
    });

    Route::get('faq-by-department/{slug}', [DepartmentFaqController::class, 'index'])->name('faq.by.department');
    Route::resource('department-faq', DepartmentFaqController::class)->names('department-faq')->only(['store', 'update', 'destroy']);
    Route::put('department-faq/status-update/{id}', [DepartmentFaqController::class, 'statusUpdate'])->name('department-faq.status-update');

    Route::get('location', [LocationController::class, 'index'])->name('location.index');
    Route::post('location', [LocationController::class, 'store'])->name('location.store');
    Route::post('location/{id}', [LocationController::class, 'update'])->name('location.update');
    Route::delete('location/{id}', [LocationController::class, 'destroy'])->name('location.destroy');
    Route::put('/location/status-update/{id}', [LocationController::class, 'statusUpdate'])->name('location.status-update');

    Route::resource('lawyer', LawyerController::class)->names('lawyer')->except('show');
    Route::put('/lawyer/status-update/{id}', [LawyerController::class, 'statusUpdate'])->name('lawyer.status-update');
    Route::post('lawyer-send-verify-mail/{id}', [LawyerController::class, 'send_verify_request'])->name('lawyer-send-verify-mail');
    Route::post('lawyer-send-verify-mail-to-all', [LawyerController::class, 'send_verify_request_to_all'])->name('lawyer-send-verify-mail-to-all');

    Route::get('leave', [LeaveController::class, 'index'])->name('leave.index');
    Route::get('leave/{id}', [LeaveController::class, 'show'])->name('leave.show');
    Route::delete('leave/{id}', [LeaveController::class, 'destroy'])->name('leave.destroy');
    Route::put('leave/status-update/{id}', [LeaveController::class, 'statusUpdate'])->name('leave.status-update');
});
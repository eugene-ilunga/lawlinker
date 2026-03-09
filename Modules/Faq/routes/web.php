<?php

use Illuminate\Support\Facades\Route;
use Modules\Faq\app\Http\Controllers\FaqCategoryController;
use Modules\Faq\app\Http\Controllers\FaqController;

Route::middleware(['auth:admin', 'translation'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('faq-by-category/{slug}', [FaqController::class, 'index'])->name('faq.by.category');
    Route::resource('faq', FaqController::class)->names('faq')->only(['store', 'update', 'destroy']);
    Route::put('/faq/status-update/{id}', [FaqController::class, 'statusUpdate'])->name('faq.status-update');

    Route::resource('faq-category', FaqCategoryController::class)->names('faq-category')->except('show');
    Route::put('/faq-category/status-update/{id}', [FaqCategoryController::class, 'statusUpdate'])->name('faq-category.status-update');
});

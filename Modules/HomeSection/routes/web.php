<?php

use Illuminate\Support\Facades\Route;
use Modules\HomeSection\app\Http\Controllers\SliderController;
use Modules\HomeSection\app\Http\Controllers\CounterController;
use Modules\HomeSection\app\Http\Controllers\FeatureController;
use Modules\HomeSection\app\Http\Controllers\PartnerController;
use Modules\HomeSection\app\Http\Controllers\SectionControlController;
use Modules\HomeSection\app\Http\Controllers\WorkSectionController;
use Modules\HomeSection\app\Http\Controllers\WorkSectionFaqController;

Route::middleware(['auth:admin', 'translation'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('work-section', [WorkSectionController::class, 'index'])->name('work-section.index');
    Route::put('work-section', [WorkSectionController::class, 'update'])->name('work-section.update');

    Route::resource('work-section-faq', WorkSectionFaqController::class)->names('work-section-faq')->only(['store', 'update', 'destroy']);
    Route::put('/work-section-faq/status-update/{id}', [WorkSectionFaqController::class, 'statusUpdate'])->name('work-section-faq.status-update');

    Route::get('counter', [CounterController::class,'index'])->name('counter.index');
    Route::post('counter', [CounterController::class,'store'])->name('counter.store');
    Route::post('counter/{id}', [CounterController::class,'update'])->name('counter.update');
    Route::delete('counter/{id}', [CounterController::class,'destroy'])->name('counter.destroy');
    Route::put('/counter/status-update/{id}', [CounterController::class, 'statusUpdate'])->name('counter.status-update');

    Route::get('partner', [PartnerController::class,'index'])->name('partner.index');
    Route::post('partner', [PartnerController::class,'store'])->name('partner.store');
    Route::post('partner/{id}', [PartnerController::class,'update'])->name('partner.update');
    Route::delete('partner/{id}', [PartnerController::class,'destroy'])->name('partner.destroy');
    Route::put('/partner/status-update/{id}', [PartnerController::class, 'statusUpdate'])->name('partner.status-update');

    Route::get('slider', [SliderController::class,'index'])->name('slider.index');
    Route::post('slider', [SliderController::class,'store'])->name('slider.store');
    Route::post('slider/{id}', [SliderController::class,'update'])->name('slider.update');
    Route::delete('slider/{id}', [SliderController::class,'destroy'])->name('slider.destroy');
    Route::put('/slider/status-update/{id}', [SliderController::class, 'statusUpdate'])->name('slider.status-update');

    Route::get('feature', [FeatureController::class,'index'])->name('feature.index');
    Route::post('feature', [FeatureController::class,'store'])->name('feature.store');
    Route::post('feature/{id}', [FeatureController::class,'update'])->name('feature.update');
    Route::delete('feature/{id}', [FeatureController::class,'destroy'])->name('feature.destroy');
    Route::put('/feature/status-update/{id}', [FeatureController::class, 'statusUpdate'])->name('feature.status-update');

    Route::get('section-control', [SectionControlController::class, 'index'])->name('section-control.index');
    Route::put('section-control', [SectionControlController::class, 'update'])->name('section-control.update');

});

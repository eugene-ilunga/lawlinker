<?php

use Illuminate\Support\Facades\Route;
use Modules\Schedule\app\Http\Controllers\ScheduleController;

Route::middleware(['auth:admin', 'translation'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::post('schedule', [ScheduleController::class, 'store'])->name('schedule.store');
    Route::post('schedule/{id}', [ScheduleController::class, 'update'])->name('schedule.update');
    Route::delete('schedule/{id}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');
    Route::put('/schedule/status-update/{id}', [ScheduleController::class, 'statusUpdate'])->name('schedule.status-update');
});

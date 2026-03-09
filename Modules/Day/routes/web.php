<?php

use Illuminate\Support\Facades\Route;
use Modules\Day\app\Http\Controllers\DayController;

Route::middleware(['auth:admin', 'translation'])->name('admin.')->prefix('admin')->group(function () {
    Route::resource('day', DayController::class)->names('day')->only(['index','update']);
    Route::put('/day/status-update/{id}', [DayController::class, 'statusUpdate'])->name('day.status-update');
});

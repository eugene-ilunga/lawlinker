<?php

use Illuminate\Support\Facades\Route;
use Modules\Leave\app\Http\Controllers\LeaveController;

Route::middleware(['maintenance.mode','auth:lawyer', 'translation'])->name('lawyer.')->prefix('lawyer')->group(function () {
    Route::get('leave', [LeaveController::class, 'index'])->name('leave.index');
    Route::post('leave', [LeaveController::class, 'store'])->name('leave.store');
    Route::post('leave/{id}', [LeaveController::class, 'update'])->name('leave.update');
    Route::delete('leave/{id}', [LeaveController::class, 'destroy'])->name('leave.destroy');
});
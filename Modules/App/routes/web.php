<?php

use Illuminate\Support\Facades\Route;
use Modules\App\app\Http\Controllers\AppController;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'translation']], function () {
    Route::resource('app/on-boarding-screen', AppController::class)->names('app.screen')->except('show');
    Route::put('app/on-boarding-screen/status-update/{id}', [AppController::class, 'statusUpdate'])->name('app.screen.status-update');
    Route::put('app-banner', [AppController::class, 'app_banner'])->name('app.banner.update');
    Route::put('app-store', [AppController::class, 'app_store'])->name('app.store.update');
});

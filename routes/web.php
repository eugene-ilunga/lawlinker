<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

//maintenance mode route
Route::get('/maintenance-mode', function () {
    $setting = Illuminate\Support\Facades\Cache::get('setting', null);
    if (! $setting?->maintenance_mode) {
        return redirect()->route('home');
    }
    return view('maintenance');
})->name('maintenance.mode');

Route::get('set-language', [DashboardController::class, 'setLanguage'])->name('set-language');
Route::get('set-currency', [DashboardController::class, 'setCurrency'])->name('set-currency');

require __DIR__ . '/admin.php';
require __DIR__ . '/lawyer.php';
require __DIR__ . '/client.php';
require __DIR__ . '/website.php';

Route::fallback(function () {
    abort(404);
});
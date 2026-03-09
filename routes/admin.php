<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RolesController;
/*  Start Admin panel Controller  */
use App\Http\Controllers\Admin\FaqPageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AboutUsPageController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

/*  End Admin panel Controller  */

Route::group(['middleware'=>'translation', 'as' => 'admin.', 'prefix' => 'admin'], function () {
    /* Start admin auth route */
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('store-login', [AuthenticatedSessionController::class, 'store'])->name('store-login');
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forget-password', [PasswordResetLinkController::class, 'custom_forget_password'])->name('forget-password');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'custom_reset_password_page'])->name('password.reset');
    Route::post('/reset-password-store/{token}', [NewPasswordController::class, 'custom_reset_password_store'])->name('password.reset-store');
    /* End admin auth route */
    
    Route::middleware(['auth:admin'])->group(function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
        Route::get('/', [DashboardController::class, 'dashboard']);
        Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

        Route::controller(AdminProfileController::class)->group(function () {
            Route::get('edit-profile', 'edit_profile')->name('edit-profile');
            Route::put('profile-update', 'profile_update')->name('profile-update');
            Route::put('update-password', 'update_password')->name('update-password');
        });

        Route::get('role/assign', [RolesController::class, 'assignRoleView'])->name('role.assign');
        Route::post('role/assign/{id}', [RolesController::class, 'getAdminRoles'])->name('role.assign.admin');
        Route::put('role/assign', [RolesController::class, 'assignRoleUpdate'])->name('role.assign.update');
        Route::resource('/role', RolesController::class);
        Route::resource('/role', RolesController::class);
    });
    Route::resource('admin', AdminController::class)->except('show');
    Route::put('admin-status/{id}', [AdminController::class, 'changeStatus'])->name('admin.status');
    // Settings routes
    Route::get('settings', [SettingController::class, 'settings'])->name('settings');

    Route::name('pages.')->prefix('pages')->group(function () {
        Route::get('about-us', [AboutUsPageController::class, 'index'])->name('about-us.index');
        Route::put('about-us', [AboutUsPageController::class, 'update'])->name('about-us.update');
        Route::get('faq', [FaqPageController::class, 'index'])->name('faq.index');
        Route::put('faq', [FaqPageController::class, 'update'])->name('faq.update');
    });
    Route::get('sync-modules', [App\Http\Controllers\Admin\AddonsController::class, 'syncModules'])->name('addons.sync');
});

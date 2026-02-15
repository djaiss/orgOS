<?php

use App\Http\Controllers\App\Organization\OrganizationController;
use App\Http\Controllers\App\Settings\LogController;
use App\Http\Controllers\App\Settings\PasswordController;
use App\Http\Controllers\App\Settings\RecoveryCodeController;
use App\Http\Controllers\App\Settings\SecurityController;
use App\Http\Controllers\App\Settings\SettingsController;
use App\Http\Controllers\App\Settings\TwoFAController;
use App\Http\Controllers\LocaleController;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): Factory|View => view('welcome'));

Route::put('/locale', [LocaleController::class, 'update'])->name('locale.update');

Route::middleware(['auth', 'verified', 'throttle:60,1', 'set.locale'])->group(function (): void {
    Route::get('organizations', [OrganizationController::class, 'index'])->name('organization.index');
    Route::get('organizations/create', [OrganizationController::class, 'create'])->name('organization.create');
    Route::post('organizations', [OrganizationController::class, 'store'])->name('organization.store');

    Route::middleware(['organization'])->group(function (): void {
        Route::get('organizations/{slug}', [OrganizationController::class, 'show'])->name('organization.show');
    });

    // settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings/profile', [SettingsController::class, 'update'])->name('settings.profile.update');

    // log dedicated page
    Route::get('settings/logs', [LogController::class, 'index'])->name('settings.logs.index');

    // security
    Route::get('settings/security', [SecurityController::class, 'index'])->name('settings.security.index');
    Route::put('settings/security/password', [PasswordController::class, 'update'])->name('settings.security.password.update');

    // 2fa
    Route::get('settings/security/2fa/create', [TwoFAController::class, 'create'])->name('settings.security.2fa.create');
    Route::post('settings/security/2fa', [TwoFAController::class, 'store'])->name('settings.security.2fa.store');
    Route::get('settings/security/recovery-codes', [RecoveryCodeController::class, 'show'])->name('settings.security.recoverycodes.show');
});

require __DIR__.'/auth.php';

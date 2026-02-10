<?php

use App\Http\Controllers\App\Organization\OrganizationController;
use App\Http\Controllers\LocaleController;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): Factory|View => view('welcome'));

Route::put('/locale', [LocaleController::class, 'update'])->name('locale.update');

Route::middleware(['auth', 'verified', 'throttle:60,1'])->group(function (): void {
    Route::get('organizations', [OrganizationController::class, 'index'])->name('organization.index');

    Route::middleware(['organization'])->group(function (): void {
        Route::get('organizations/{slug}', [OrganizationController::class, 'show'])->name('organizations.show');
    });
});

require __DIR__.'/auth.php';

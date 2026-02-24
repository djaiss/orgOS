<?php

declare(strict_types=1);

use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function (): void {
    Route::get('health', [HealthController::class, 'show'])->middleware('throttle:60,1');

    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function (): void {
        // organizations
        Route::post('organizations', [OrganizationController::class, 'create'])->name('organization.create');
        Route::get('organizations', [OrganizationController::class, 'index'])->name('organization.index');
        Route::middleware(['organization.api'])->group(function (): void {
            Route::get('organizations/{id}', [OrganizationController::class, 'show'])->name('organization.show');
        });
    });
});

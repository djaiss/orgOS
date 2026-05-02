<?php

declare(strict_types=1);

use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\Adminland\OfficeController;
use App\Http\Controllers\Api\Adminland\OfficeTypeController;
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
            Route::put('organizations/{id}', [OrganizationController::class, 'update'])->name('organization.update');
            Route::delete('organizations/{id}', [OrganizationController::class, 'destroy'])->name('organization.destroy');

            // adminland - office types
            Route::get('organizations/{id}/adminland/officetypes', [OfficeTypeController::class, 'index'])->name('organization.adminland.officetype.index');
            Route::post('organizations/{id}/adminland/officetypes', [OfficeTypeController::class, 'store'])->name('organization.adminland.officetype.store');
            Route::get('organizations/{id}/adminland/officetypes/{officeTypeId}', [OfficeTypeController::class, 'show'])->name('organization.adminland.officetype.show');
            Route::put('organizations/{id}/adminland/officetypes/{officeTypeId}', [OfficeTypeController::class, 'update'])->name('organization.adminland.officetype.update');
            Route::delete('organizations/{id}/adminland/officetypes/{officeTypeId}', [OfficeTypeController::class, 'destroy'])->name('organization.adminland.officetype.destroy');

            // adminland - offices
            Route::get('organizations/{id}/adminland/offices', [OfficeController::class, 'index'])->name('organization.adminland.office.index');
            Route::post('organizations/{id}/adminland/offices', [OfficeController::class, 'store'])->name('organization.adminland.office.store');
            Route::get('organizations/{id}/adminland/offices/{officeId}', [OfficeController::class, 'show'])->name('organization.adminland.office.show');
            Route::put('organizations/{id}/adminland/offices/{officeId}', [OfficeController::class, 'update'])->name('organization.adminland.office.update');
            Route::delete('organizations/{id}/adminland/offices/{officeId}', [OfficeController::class, 'destroy'])->name('organization.adminland.office.destroy');
        });
    });
});

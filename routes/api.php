<?php

declare(strict_types=1);

use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\Adminland\DepartmentController;
use App\Http\Controllers\Api\Adminland\MemberController;
use App\Http\Controllers\Api\Adminland\MemberTypeController;
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

            // adminland - members
            Route::get('organizations/{id}/adminland/members', [MemberController::class, 'index'])->name('organization.adminland.member.index');
            Route::get('organizations/{id}/adminland/members/{memberId}', [MemberController::class, 'show'])->name('organization.adminland.member.show');

            // adminland - member types
            Route::get('organizations/{id}/adminland/membertypes', [MemberTypeController::class, 'index'])->name('organization.adminland.membertype.index');
            Route::post('organizations/{id}/adminland/membertypes', [MemberTypeController::class, 'store'])->name('organization.adminland.membertype.store');
            Route::get('organizations/{id}/adminland/membertypes/{memberTypeId}', [MemberTypeController::class, 'show'])->name('organization.adminland.membertype.show');
            Route::put('organizations/{id}/adminland/membertypes/{memberTypeId}', [MemberTypeController::class, 'update'])->name('organization.adminland.membertype.update');
            Route::delete('organizations/{id}/adminland/membertypes/{memberTypeId}', [MemberTypeController::class, 'destroy'])->name('organization.adminland.membertype.destroy');

            // adminland - departments
            Route::get('organizations/{id}/adminland/departments', [DepartmentController::class, 'index'])->name('organization.adminland.department.index');
            Route::post('organizations/{id}/adminland/departments', [DepartmentController::class, 'store'])->name('organization.adminland.department.store');
            Route::get('organizations/{id}/adminland/departments/{departmentId}', [DepartmentController::class, 'show'])->name('organization.adminland.department.show');
            Route::put('organizations/{id}/adminland/departments/{departmentId}', [DepartmentController::class, 'update'])->name('organization.adminland.department.update');
            Route::delete('organizations/{id}/adminland/departments/{departmentId}', [DepartmentController::class, 'destroy'])->name('organization.adminland.department.destroy');
        });
    });
});

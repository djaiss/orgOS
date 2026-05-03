<?php

declare(strict_types=1);

use App\Enums\Permission;
use App\Http\Controllers\App\Organization\Adminland\AdminlandController;
use App\Http\Controllers\App\Organization\Adminland\DepartmentController;
use App\Http\Controllers\App\Organization\Adminland\MemberController;
use App\Http\Controllers\App\Organization\Adminland\MemberTypeController;
use App\Http\Controllers\App\Organization\Adminland\OfficeController;
use App\Http\Controllers\App\Organization\Adminland\OfficeTypeController;
use App\Http\Controllers\App\Organization\JoinOrganizationController;
use App\Http\Controllers\App\Organization\OrganizationController;
use App\Http\Controllers\App\Settings\ApiKeyController;
use App\Http\Controllers\App\Settings\AutoDeleteAccountController;
use App\Http\Controllers\App\Settings\EmailSentController;
use App\Http\Controllers\App\Settings\LogController;
use App\Http\Controllers\App\Settings\PasswordController;
use App\Http\Controllers\App\Settings\RecoveryCodeController;
use App\Http\Controllers\App\Settings\SecurityController;
use App\Http\Controllers\App\Settings\SettingsController;
use App\Http\Controllers\App\Settings\TwoFAController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/marketing.php';

Route::put('/locale', [LocaleController::class, 'update'])->name('locale.update');

Route::middleware(['auth', 'verified', 'throttle:60,1', 'set.locale'])->group(function (): void {
    Route::get('organizations', [OrganizationController::class, 'index'])->name('organization.index');

    // create
    Route::get('organizations/create', [OrganizationController::class, 'create'])->name('organization.create');
    Route::post('organizations', [OrganizationController::class, 'store'])->name('organization.store');

    // join
    Route::get('organizations/join', [JoinOrganizationController::class, 'create'])->name('organization.join.create');
    Route::post('organizations/join', [JoinOrganizationController::class, 'store'])->name('organization.join.store');

    Route::middleware(['organization'])->group(function (): void {
        Route::get('organizations/{slug}', [OrganizationController::class, 'show'])->name('organization.show');

        // adminland
        Route::middleware(['permission:' . Permission::Owner->value . ',' . Permission::Admin->value])->group(function (): void {
            Route::get('organizations/{slug}/adminland', [AdminlandController::class, 'index'])->name('organization.adminland.index');

            // members
            Route::get('organizations/{slug}/adminland/members', [MemberController::class, 'index'])->name('organization.adminland.member.index');

            // member types
            Route::get('organizations/{slug}/adminland/member-types/create', [MemberTypeController::class, 'create'])->name('organization.adminland.member_type.create');
            Route::post('organizations/{slug}/adminland/member-types', [MemberTypeController::class, 'store'])->name('organization.adminland.member_type.store');
            Route::get('organizations/{slug}/adminland/member-types/{memberType}', [MemberTypeController::class, 'edit'])->name('organization.adminland.member_type.edit');
            Route::put('organizations/{slug}/adminland/member-types/{memberType}', [MemberTypeController::class, 'update'])->name('organization.adminland.member_type.update');
            Route::delete('organizations/{slug}/adminland/member-types/{memberType}', [MemberTypeController::class, 'destroy'])->name('organization.adminland.member_type.destroy');

            // departments
            Route::get('organizations/{slug}/adminland/departments', [DepartmentController::class, 'index'])->name('organization.adminland.department.index');
            Route::get('organizations/{slug}/adminland/departments/create', [DepartmentController::class, 'create'])->name('organization.adminland.department.create');
            Route::post('organizations/{slug}/adminland/departments', [DepartmentController::class, 'store'])->name('organization.adminland.department.store');
            Route::get('organizations/{slug}/adminland/departments/{department}', [DepartmentController::class, 'edit'])->name('organization.adminland.department.edit');
            Route::put('organizations/{slug}/adminland/departments/{department}', [DepartmentController::class, 'update'])->name('organization.adminland.department.update');
            Route::delete('organizations/{slug}/adminland/departments/{department}', [DepartmentController::class, 'destroy'])->name('organization.adminland.department.destroy');

            // offices
            Route::get('organizations/{slug}/adminland/offices', [OfficeController::class, 'index'])->name('organization.adminland.office.index');
            Route::get('organizations/{slug}/adminland/offices/create', [OfficeController::class, 'create'])->name('organization.adminland.office.create');
            Route::post('organizations/{slug}/adminland/offices', [OfficeController::class, 'store'])->name('organization.adminland.office.store');
            Route::get('organizations/{slug}/adminland/offices/{office}', [OfficeController::class, 'edit'])->name('organization.adminland.office.edit');
            Route::put('organizations/{slug}/adminland/offices/{office}', [OfficeController::class, 'update'])->name('organization.adminland.office.update');
            Route::delete('organizations/{slug}/adminland/offices/{office}', [OfficeController::class, 'destroy'])->name('organization.adminland.office.destroy');
            Route::get('organizations/{slug}/adminland/office-types/create', [OfficeTypeController::class, 'create'])->name('organization.adminland.office_type.create');
            Route::post('organizations/{slug}/adminland/office-types', [OfficeTypeController::class, 'store'])->name('organization.adminland.office_type.store');
            Route::get('organizations/{slug}/adminland/office-types/{officeType}', [OfficeTypeController::class, 'edit'])->name('organization.adminland.office_type.edit');
            Route::put('organizations/{slug}/adminland/office-types/{officeType}', [OfficeTypeController::class, 'update'])->name('organization.adminland.office_type.update');
            Route::delete('organizations/{slug}/adminland/office-types/{officeType}', [OfficeTypeController::class, 'destroy'])->name('organization.adminland.office_type.destroy');
        });
    });

    // settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings/profile', [SettingsController::class, 'update'])->name('settings.profile.update');

    // log dedicated page
    Route::get('settings/logs', [LogController::class, 'index'])->name('settings.logs.index');

    // emails dedicated page
    Route::get('settings/emails', [EmailSentController::class, 'index'])->name('settings.emails.index');

    // security
    Route::get('settings/security', [SecurityController::class, 'index'])->name('settings.security.index');
    Route::put('settings/security/password', [PasswordController::class, 'update'])->name('settings.security.password.update');

    // 2fa
    Route::get('settings/security/2fa/create', [TwoFAController::class, 'create'])->name('settings.security.2fa.create');
    Route::post('settings/security/2fa', [TwoFAController::class, 'store'])->name('settings.security.2fa.store');
    Route::delete('settings/security/2fa', [TwoFAController::class, 'destroy'])->name('settings.security.2fa.destroy');
    Route::get('settings/security/recovery-codes', [RecoveryCodeController::class, 'show'])->name('settings.security.recoverycodes.show');

    // auto delete account
    Route::put('settings/security/auto-delete-account', [AutoDeleteAccountController::class, 'update'])->name('settings.security.auto-delete.update');

    // api
    Route::get('settings/api-keys/create', [ApiKeyController::class, 'create'])->name('settings.api-keys.create');
    Route::post('settings/api-keys', [ApiKeyController::class, 'store'])->name('settings.api-keys.store');
    Route::delete('settings/api-keys/{apiKey}', [ApiKeyController::class, 'destroy'])->name('settings.api-keys.destroy');
});

require __DIR__ . '/auth.php';

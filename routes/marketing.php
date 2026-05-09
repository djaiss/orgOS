<?php

declare(strict_types=1);

use App\Http\Controllers\Marketing\Docs\AdminlandDepartmentController;
use App\Http\Controllers\Marketing\Docs\AdminlandDepartmentManageController;
use App\Http\Controllers\Marketing\Docs\AdminlandOfficeController;
use App\Http\Controllers\Marketing\Docs\AdminlandOfficeManageController;
use App\Http\Controllers\Marketing\Docs\ApiDepartmentController;
use App\Http\Controllers\Marketing\Docs\ApiIntroductionController;
use App\Http\Controllers\Marketing\Docs\ApiMemberController;
use App\Http\Controllers\Marketing\Docs\ApiMemberTypeController;
use App\Http\Controllers\Marketing\Docs\ApiOfficeController;
use App\Http\Controllers\Marketing\Docs\ApiOfficeTypeController;
use App\Http\Controllers\Marketing\Docs\ApiOrganizationController;
use App\Http\Controllers\Marketing\Docs\DocsIndexController;
use App\Http\Controllers\Marketing\Docs\DocsOrganizationController;
use App\Http\Controllers\Marketing\MarketingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['marketing'])->group(function (): void {
    Route::get('/', [MarketingController::class, 'index'])->name('marketing.index');

    // docs index
    Route::get('/docs', [DocsIndexController::class, 'index'])->name('marketing.docs.index');
    Route::get('/docs/organizations', [DocsOrganizationController::class, 'index'])->name('marketing.docs.organizations.index');

    // api docs
    Route::get('/docs/api', [ApiIntroductionController::class, 'index'])->name('marketing.docs.api.index');
    Route::get('/docs/api/organizations', [ApiOrganizationController::class, 'index'])->name('marketing.docs.api.organizations.index');
    Route::get('/docs/api/organizations/officetypes', [ApiOfficeTypeController::class, 'index'])->name('marketing.docs.api.organizations.officetypes.index');
    Route::get('/docs/api/organizations/offices', [ApiOfficeController::class, 'index'])->name('marketing.docs.api.organizations.offices.index');
    Route::get('/docs/api/organizations/members', [ApiMemberController::class, 'index'])->name('marketing.docs.api.organizations.members.index');
    Route::get('/docs/api/organizations/membertypes', [ApiMemberTypeController::class, 'index'])->name('marketing.docs.api.organizations.membertypes.index');
    Route::get('/docs/api/organizations/departments', [ApiDepartmentController::class, 'index'])->name('marketing.docs.api.organizations.departments.index');

    // adminland docs
    Route::get('/docs/offices', [AdminlandOfficeController::class, 'index'])->name('marketing.docs.offices.index');
    Route::get('/docs/offices/manage', [AdminlandOfficeManageController::class, 'index'])->name('marketing.docs.offices.manage');
    Route::get('/docs/departments', [AdminlandDepartmentController::class, 'index'])->name('marketing.docs.departments.index');
    Route::get('/docs/departments/manage', [AdminlandDepartmentManageController::class, 'index'])->name('marketing.docs.departments.manage');
});

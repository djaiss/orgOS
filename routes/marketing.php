<?php

declare(strict_types=1);

use App\Http\Controllers\Marketing\Docs\ApiIntroductionController;
use App\Http\Controllers\Marketing\Docs\ApiMemberController;
use App\Http\Controllers\Marketing\Docs\ApiOfficeController;
use App\Http\Controllers\Marketing\Docs\ApiOfficeTypeController;
use App\Http\Controllers\Marketing\Docs\ApiOrganizationController;
use App\Http\Controllers\Marketing\MarketingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['marketing'])->group(function (): void {
    Route::get('/', [MarketingController::class, 'index'])->name('marketing.index');

    // api docs
    Route::get('/docs/api', [ApiIntroductionController::class, 'index'])->name('marketing.docs.api.index');
    Route::get('/docs/api/organizations', [ApiOrganizationController::class, 'index'])->name('marketing.docs.api.organizations.index');
    Route::get('/docs/api/organizations/officetypes', [ApiOfficeTypeController::class, 'index'])->name('marketing.docs.api.organizations.officetypes.index');
    Route::get('/docs/api/organizations/offices', [ApiOfficeController::class, 'index'])->name('marketing.docs.api.organizations.offices.index');
    Route::get('/docs/api/organizations/members', [ApiMemberController::class, 'index'])->name('marketing.docs.api.organizations.members.index');
});

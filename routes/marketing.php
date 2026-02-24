<?php

declare(strict_types=1);

use App\Http\Controllers\Marketing\Docs\ApiIntroductionController;
use App\Http\Controllers\Marketing\MarketingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['marketing'])->group(function (): void {
    Route::get('/', [MarketingController::class, 'index'])->name('marketing.index');

    // api docs
    Route::get('/docs/api', [ApiIntroductionController::class, 'index'])->name('marketing.docs.api.index');
});

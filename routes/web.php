<?php

use App\Http\Controllers\App\DashboardController;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): Factory|View => view('welcome'));

Route::middleware(['auth', 'verified', 'throttle:60,1'])->group(function (): void {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
});

require __DIR__.'/auth.php';

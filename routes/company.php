<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\DashboardController;

Route::middleware(['auth', 'role:Admin Empresa'])
    ->prefix('company')
    ->name('company.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

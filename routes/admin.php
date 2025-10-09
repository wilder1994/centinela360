<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CompanyController;

Route::middleware(['auth', 'role:Super Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
        Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
});

<?php

use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:Super Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('companies', CompanyController::class)->except(['show']);
        Route::patch('/companies/{company}/toggle', [CompanyController::class, 'toggleStatus'])->name('companies.toggle');

        Route::resource('users', UserController::class)->except(['show']);
        Route::patch('/users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
    });

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\DashboardController;
use App\Http\Controllers\Company\UserController;

Route::middleware(['auth', 'role:Super Admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('company.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('company.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('company.users.create');
});

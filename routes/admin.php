<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\UserController;  // Asegúrate de agregar esta línea al principio del archivo

Route::middleware(['auth', 'role:Super Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // 📊 Dashboard principal del Super Admin
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // 🏢 CRUD de Empresas
        Route::resource('companies', CompanyController::class)
            ->except(['show']);

        // 🔁 Activar / Suspender empresa
        Route::patch('/companies/{company}/toggle', [CompanyController::class, 'toggleStatus'])
            ->name('companies.toggle');

        // 🚶‍♂️ CRUD de Usuarios
        Route::resource('users', UserController::class)  // Agregar esta línea para manejar las rutas de usuarios
            ->except(['show']);
        
        // 🔁 Activar / Suspender usuarios
        Route::patch('/users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');

    });

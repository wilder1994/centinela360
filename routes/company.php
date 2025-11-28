<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\ClientController;
use App\Http\Controllers\Company\DashboardController;
use App\Http\Controllers\Company\EmployeeController;

/*
|--------------------------------------------------------------------------
| Company routes (empresa)
|--------------------------------------------------------------------------
|
| Rutas del panel de empresa. Están protegidas por auth y role:Admin Empresa.
| Nombre de rutas: company.*
|
*/

Route::middleware(['auth', 'role:Admin Empresa'])
    ->prefix('company')
    ->name('company.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Programming (vista simple)
        Route::view('programming', 'company.programming.index')->name('programming.index');

        // ---- BASE DE DATOS - EMPLEADOS ----
        Route::prefix('employees')->name('employees.')->group(function () {
            Route::get('/', [EmployeeController::class, 'index'])->name('index');
            Route::get('/create', [EmployeeController::class, 'create'])->name('create');
            Route::post('/', [EmployeeController::class, 'store'])->name('store');
            Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
            Route::put('/{employee}', [EmployeeController::class, 'update'])->name('update');
            Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');
        });

        // ---- BASE DE DATOS - CLIENTES ----
        Route::prefix('clients')->name('clients.')->group(function () {
            Route::get('/', [ClientController::class, 'index'])->name('index');
            Route::get('/create', [ClientController::class, 'create'])->name('create');
            Route::post('/', [ClientController::class, 'store'])->name('store');
            Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
            Route::put('/{client}', [ClientController::class, 'update'])->name('update');
            Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');
        });

        // Aquí puedes adicionar más secciones del panel de empresa en el futuro...

    });

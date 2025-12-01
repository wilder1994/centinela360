<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\ClientController;
use App\Http\Controllers\Company\DashboardController;
use App\Http\Controllers\Company\EmployeeController;
use App\Http\Controllers\Company\MemorandumController;

/*
|--------------------------------------------------------------------------
| Company routes (empresa)
|--------------------------------------------------------------------------
|
| Rutas del panel de empresa. Estan protegidas por auth y role:Admin Empresa.
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

        // ---- MEMORANDOS ----
        Route::get('/memorandums/pendientes', function () {
            return view('company.memorandums.pendientes-page');
        })->name('memorandums.pendientes');
        Route::get('/memorandums/en-proceso', function () {
            return view('company.memorandums.en-proceso-page');
        })->name('memorandums.en_proceso');
        Route::get('/memorandums/finalizados', function () {
            return view('company.memorandums.finalizados-page');
        })->name('memorandums.finalizados');

        Route::get('memorandums/{memorandum}/board', [MemorandumController::class, 'edit'])->name('memorandums.board');
        Route::resource('memorandums', MemorandumController::class);

        // Aqui puedes adicionar mas secciones del panel de empresa en el futuro...

    });

Route::middleware(['auth', 'role:Admin Empresa'])
    ->prefix('company')
    ->name('company.')
    ->group(function () {
        Route::get('users', \App\Livewire\Company\Users\Index::class)->name('users.index');
        Route::get('users/create', \App\Livewire\Company\Users\Create::class)->name('users.create');
        Route::get('users/{user}/edit', \App\Livewire\Company\Users\Edit::class)->name('users.edit');
    });

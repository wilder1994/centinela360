<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\ClientController;
use App\Http\Controllers\Company\DashboardController;
use App\Http\Controllers\Company\MemorandumController;
use App\Http\Controllers\Company\EmployeeController;
use App\Livewire\Memorandums\Board;
use App\Livewire\Memorandums\Finalized;

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

        // ---- MEMORÁNDUMS ----
        Route::prefix('memorandums')->name('memorandums.')->group(function () {
            // 🟢 PRIMERO: rutas específicas (no deben ir después de /{memorandum})
            Route::get('/board', Board::class)->name('board');
            Route::get('/finalized', Finalized::class)->name('finalized');

            // CRUD clásico
            Route::get('/', [MemorandumController::class, 'index'])->name('index');
            Route::get('/create', [MemorandumController::class, 'create'])->name('create');
            Route::post('/', [MemorandumController::class, 'store'])->name('store');

            // Rutas con parámetro -> las dejamos al final y las restringimos a números
            Route::get('/{memorandum}', [MemorandumController::class, 'show'])
                ->whereNumber('memorandum')
                ->name('show');

            Route::get('/{memorandum}/edit', [MemorandumController::class, 'edit'])
                ->whereNumber('memorandum')
                ->name('edit');

            Route::put('/{memorandum}', [MemorandumController::class, 'update'])
                ->whereNumber('memorandum')
                ->name('update');

            Route::post('/{memorandum}/status', [MemorandumController::class, 'updateStatus'])
                ->whereNumber('memorandum')
                ->name('status');
        });

        // Aquí puedes adicionar más secciones del panel de empresa en el futuro...

    });

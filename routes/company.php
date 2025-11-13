<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\DashboardController;
use App\Http\Controllers\Company\MemorandumController;
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
            Route::get('/', function () {
                return view('company.employees.index');
            })->name('index');

            Route::get('/create', function () {
                return view('company.employees.create');
            })->name('create');

            Route::get('/{employee}/edit', function ($employee) {
                return view('company.employees.edit', compact('employee'));
            })->name('edit');
        });

        // ---- BASE DE DATOS - CLIENTES ----
        Route::prefix('clients')->name('clients.')->group(function () {
            Route::get('/', function () {
                return view('company.clients.index');
            })->name('index');

            Route::get('/create', function () {
                return view('company.clients.create');
            })->name('create');

            Route::get('/{client}/edit', function ($client) {
                return view('company.clients.edit', compact('client'));
            })->name('edit');
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

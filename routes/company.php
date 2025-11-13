<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\DashboardController;
use App\Http\Controllers\Company\MemorandumController;

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

        // Programming (vista simple) - queda dentro del mismo grupo, por eso la ruta será /company/programming
        Route::view('programming', 'company.programming.index')->name('programming.index');

        // ---- BASE DE DATOS - EMPLEADOS (vistas estáticas por ahora) ----
        Route::prefix('employees')->name('employees.')->group(function () {
            Route::get('/', function () {
                return view('company.employees.index');
            })->name('index');

            Route::get('/create', function () {
                return view('company.employees.create');
            })->name('create');

            Route::get('/{employee}/edit', function ($employee) {
                // si más adelante usas un controlador, reemplaza el closure por Controller@edit
                return view('company.employees.edit', compact('employee'));
            })->name('edit');
        });

        // ---- BASE DE DATOS - CLIENTES (vistas estáticas por ahora) ----
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
            Route::get('/', [MemorandumController::class, 'index'])->name('index');
            Route::get('/create', [MemorandumController::class, 'create'])->name('create');
            Route::post('/', [MemorandumController::class, 'store'])->name('store');
            Route::get('/{memorandum}', [MemorandumController::class, 'show'])->name('show');
            Route::get('/{memorandum}/edit', [MemorandumController::class, 'edit'])->name('edit');
            Route::put('/{memorandum}', [MemorandumController::class, 'update'])->name('update');
            Route::post('/{memorandum}/status', [MemorandumController::class, 'updateStatus'])->name('status');
        });

        // Aquí puedes adicionar más secciones del panel de empresa en el futuro...

    });

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\ClientController;
use App\Http\Controllers\Company\ServiceTypeController;
use App\Http\Controllers\Company\DashboardController;
use App\Http\Controllers\Company\EmployeeController;
use App\Http\Controllers\Company\EmployeeCatalogController;
use App\Http\Controllers\Company\MemorandumController;
use App\Http\Controllers\Company\MemorandumSubjectController;

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
        Route::view('programming/create', 'company.programming.create')->name('programming.create');

        // ---- BASE DE DATOS - EMPLEADOS ----
        Route::prefix('employees')->name('employees.')->group(function () {
            Route::get('/', [EmployeeController::class, 'index'])->name('index');
            Route::get('/archived', [EmployeeController::class, 'archived'])->name('archived');
            Route::get('/create', [EmployeeController::class, 'create'])->name('create');
            Route::post('/', [EmployeeController::class, 'store'])->name('store');
            Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
            Route::put('/{employee}', [EmployeeController::class, 'update'])->name('update');
            Route::post('/{employee}/notes', [EmployeeController::class, 'storeNote'])->name('notes.store');
            Route::put('/{employee}/unarchive', [EmployeeController::class, 'unarchive'])->name('unarchive');
            Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');

            Route::post('/catalogs', [EmployeeCatalogController::class, 'storeType'])->name('catalogs.store');
            Route::put('/catalogs/{catalog}/{id}', [EmployeeCatalogController::class, 'updateType'])->name('catalogs.update');
            Route::delete('/catalogs/{catalog}/{id}', [EmployeeCatalogController::class, 'destroyType'])->name('catalogs.destroy');
        });

        // ---- BASE DE DATOS - CLIENTES ----
        Route::prefix('clients')->name('clients.')->group(function () {
            Route::get('/', [ClientController::class, 'index'])->name('index');
            Route::get('/archived', [ClientController::class, 'archived'])->name('archived');
            Route::get('/create', [ClientController::class, 'create'])->name('create');
            Route::post('/', [ClientController::class, 'store'])->name('store');
            Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
            Route::put('/{client}', [ClientController::class, 'update'])->name('update');
            Route::post('/{client}/notes', [ClientController::class, 'storeNote'])->name('notes.store');
            Route::put('/{client}/unarchive', [ClientController::class, 'unarchive'])->name('unarchive');
            Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');

            Route::post('/service-types', [ServiceTypeController::class, 'store'])->name('service_types.store');
            Route::put('/service-types/{serviceType}', [ServiceTypeController::class, 'update'])->name('service_types.update');
            Route::delete('/service-types/{serviceType}', [ServiceTypeController::class, 'destroy'])->name('service_types.destroy');
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
        Route::post('memorandum-subjects', [MemorandumSubjectController::class, 'store'])->name('memorandum_subjects.store');
        Route::put('memorandum-subjects/{subject}', [MemorandumSubjectController::class, 'update'])->name('memorandum_subjects.update');
        Route::delete('memorandum-subjects/{subject}', [MemorandumSubjectController::class, 'destroy'])->name('memorandum_subjects.destroy');

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

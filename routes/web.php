<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí se registran todas las rutas web del sistema.
| Se cargan automáticamente por RouteServiceProvider.
|
*/

// Página principal
Route::get('/', function () {
    return view('welcome');
});

// Dashboard protegido con auth y verificación de correo
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas de perfil del usuario autenticado
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'role:Supervisor'])->get('/supervisor', function () {
    return 'Bienvenido al Panel del Supervisor';
});

// 🧱 Rutas protegidas por permisos
Route::middleware(['auth', 'permission:manage_users'])->get('/users', function () {
    return 'Gestión de usuarios';
});

Route::middleware(['auth', 'permission:ver-reportes'])->get('/reportes', function () {
    return 'Tienes permiso para ver reportes';
});

// Autenticación (Laravel Breeze / Jetstream)
require __DIR__.'/auth.php';

require __DIR__.'/admin.php';
require __DIR__.'/company.php';

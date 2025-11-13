<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí se registran todas las rutas web del sistema.
| Se cargan automáticamente por RouteServiceProvider.
|
*/

// 🏠 Página principal (pública)
Route::get('/', function () {
    return view('welcome');
});

// 🧭 Dashboard por defecto (solo si no aplica otro rol)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 👤 Rutas de perfil del usuario autenticado
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🔁 Redirección dinámica post-login (según rol/empresa)
Route::get('/redirect', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    // 🔹 Si es Super Admin → Dashboard administrativo
    if ($user->hasRole('Super Admin')) {
        return redirect()->route('admin.dashboard');
    }

    // 🔹 Si pertenece a una empresa → Dashboard empresarial
    if ($user->company_id) {
        return redirect()->route('company.dashboard');
    }

    // 🔹 Si no cumple ninguna de las anteriores → Dashboard genérico
    return redirect('/dashboard');
})->middleware('auth')->name('redirect');


// ⚙️ Archivos de rutas específicas
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/company.php';

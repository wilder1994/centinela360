<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| AquÃ­ se registran todas las rutas web del sistema.
| Se cargan automÃ¡ticamente por RouteServiceProvider.
|
*/

// ðŸ  PÃ¡gina principal (pÃºblica)
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Usamos la lÃ³gica de redirecciÃ³n por rol
    return redirect()->route('redirect');
})->middleware(['auth', 'verified'])->name('dashboard');

// ðŸ‘¤ Rutas de perfil del usuario autenticado
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ðŸ” RedirecciÃ³n dinÃ¡mica post-login (segÃºn rol/empresa)
Route::get('/redirect', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    // ðŸ”¹ Si es Super Admin â†’ Dashboard administrativo
    if ($user->hasRole('Super Admin')) {
        return redirect()->route('admin.dashboard');
    }

    // ðŸ”¹ Si pertenece a una empresa â†’ Dashboard empresarial
    if ($user->company_id) {
        return redirect()->route('company.dashboard');
    }

    // ðŸ”¹ Si no cumple ninguna de las anteriores â†’ Dashboard genÃ©rico
    return redirect()->route('company.dashboard');
})->middleware(['auth'])->name('redirect');


// âš™ï¸ Archivos de rutas especÃ­ficas
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/company.php';


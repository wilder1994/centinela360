<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RedirectController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // 🧭 Redirección según rol
        if ($user->hasRole('Super Admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('Admin Empresa')) {
            return redirect()->route('company.dashboard');
        }

        // Si no tiene roles de gestión, se va al dashboard general
        return redirect()->route('dashboard');
    }
}

<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    /**
     * Dashboard genérico cuando no aplica un panel de rol específico.
     */
    public function __invoke()
    {
        return view('dashboard');
    }
}

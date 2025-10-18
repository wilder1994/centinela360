<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Totales principales
        $totalCompanies = Company::count();
        $totalUsers = User::count();
        $totalReports = 0; // 🔸 placeholder, lo conectaremos cuando exista el módulo de informes
        $totalAlerts = 0;  // 🔸 placeholder

        // Últimos registros (actividad reciente)
        $recentCompanies = Company::latest()->take(3)->get(['name', 'created_at']);
        $recentUsers = User::latest()->take(3)->get(['name', 'email', 'created_at']);

        // Datos del gráfico (simulados temporalmente)
        $activityData = [
            ['day' => 'Lun', 'count' => rand(5, 20)],
            ['day' => 'Mar', 'count' => rand(5, 20)],
            ['day' => 'Mié', 'count' => rand(5, 20)],
            ['day' => 'Jue', 'count' => rand(5, 20)],
            ['day' => 'Vie', 'count' => rand(5, 20)],
            ['day' => 'Sáb', 'count' => rand(5, 20)],
            ['day' => 'Dom', 'count' => rand(5, 20)],
        ];

        return view('admin.dashboard.index', compact(
            'totalCompanies',
            'totalUsers',
            'totalReports',
            'totalAlerts',
            'activityData',
            'recentCompanies',
            'recentUsers'
        ));
    }
}

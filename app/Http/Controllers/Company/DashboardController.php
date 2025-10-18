<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Report; // Si aún no existe, luego lo crearemos

class DashboardController extends Controller
{
    public function index()
    {
        $company = Auth::user()->company;

        if (!$company) {
            abort(403, 'El usuario no está asociado a ninguna empresa.');
        }

        $totalUsers = User::where('company_id', $company->id)->count();
        $totalReports = class_exists(Report::class)
            ? Report::where('company_id', $company->id)->count()
            : 0;
        $totalAlerts = 0;

        $activityData = [
            ['day' => 'Lun', 'count' => rand(5, 15)],
            ['day' => 'Mar', 'count' => rand(5, 15)],
            ['day' => 'Mié', 'count' => rand(5, 15)],
            ['day' => 'Jue', 'count' => rand(5, 15)],
            ['day' => 'Vie', 'count' => rand(5, 15)],
            ['day' => 'Sáb', 'count' => rand(5, 15)],
            ['day' => 'Dom', 'count' => rand(5, 15)],
        ];

        return view('company.dashboard.index', compact(
            'company',
            'totalUsers',
            'totalReports',
            'totalAlerts',
            'activityData'
        ));
    }
}

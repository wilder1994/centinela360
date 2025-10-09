<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use App\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic KPIs for the admin dashboard
        $companiesCount = Company::count();
        $usersCount = User::count();
        $rolesCount = Role::count();

        return view('admin.dashboard', compact('companiesCount', 'usersCount', 'rolesCount'));
    }
}

<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id;
        $usersCount = User::where('company_id', $companyId)->count();

        return view('company.dashboard', compact('usersCount'));
    }
}

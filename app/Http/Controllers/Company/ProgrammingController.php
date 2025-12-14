<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ProgrammingTurn;
use Illuminate\Http\Request;

class ProgrammingController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('company.programming.create');
    }

    public function create(Request $request)
    {
        $company = $request->user()->company;
        $turns = ProgrammingTurn::where('company_id', $company->id)
            ->orderBy('position')
            ->get();

        $clients = Client::query()
            ->where('company_id', $company->id)
            ->orderBy('business_name')
            ->get(['id', 'business_name']);

        return view('company.programming.create', compact('turns', 'clients', 'company'));
    }

    public function store(Request $request)
    {
        $company = $request->user()->company;

        $total = ProgrammingTurn::where('company_id', $company->id)->count();
        $capacity = 20;
        if ($total >= $capacity) {
            return back()->with('error', 'No cuenta con espacios para crear turnos, puede editar o eliminar.')->withInput();
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:120'],
            'color' => ['required', 'string', 'max:9'],
        ]);

        ProgrammingTurn::create([
            'company_id' => $company->id,
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'color' => $data['color'],
            'position' => $total,
        ]);

        return redirect()->route('company.programming.create')->with('status', 'Turno creado correctamente.');
    }
}

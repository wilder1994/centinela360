<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::orderBy('name')->get();
        return view('admin.companies.index', compact('companies'));
    }

    public function create()
    {
        return view('admin.companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nit' => 'required|string|max:50|unique:companies,nit',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'representative' => 'nullable|string|max:150',
            'active' => 'sometimes|boolean',
        ]);

        Company::create($validated);

        return redirect()->route('admin.companies.index')
                         ->with('success', 'Empresa creada correctamente.');
    }

    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nit' => [
                'required','string','max:50',
                Rule::unique('companies','nit')->ignore($company->id),
            ],
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'representative' => 'nullable|string|max:150',
            'active' => 'sometimes|boolean',
        ]);

        $company->update($validated);

        return redirect()->route('admin.companies.index')
                         ->with('success', 'Empresa actualizada correctamente.');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('admin.companies.index')
                         ->with('success', 'Empresa eliminada correctamente.');
    }
}

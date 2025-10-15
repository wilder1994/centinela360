<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Mostrar listado de empresas
     */
    public function index()
    {
        $companies = Company::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('admin.companies.create');
    }

    /**
     * Guardar nueva empresa
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nit' => 'required|string|max:50|unique:companies,nit',
            'email' => 'required|email|max:255|unique:companies,email',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'representative' => 'nullable|string|max:255',
            'color_primary' => 'nullable|string|max:20',
            'color_secondary' => 'nullable|string|max:20',
            'color_text' => 'nullable|string|max:20',
            'subscription_expires_at' => 'nullable|date',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('companies/logos', 'public');
        }

        Company::create($validated);

        return redirect()->route('admin.companies.index')->with('success', 'Empresa creada correctamente.');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    /**
     * Actualizar empresa existente
     */
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nit' => 'required|string|max:50|unique:companies,nit,' . $company->id,
            'email' => 'required|email|max:255|unique:companies,email,' . $company->id,
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'representative' => 'nullable|string|max:255',
            'color_primary' => 'nullable|string|max:20',
            'color_secondary' => 'nullable|string|max:20',
            'color_text' => 'nullable|string|max:20',
            'subscription_expires_at' => 'nullable|date',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($company->logo && Storage::disk('public')->exists($company->logo)) {
                Storage::disk('public')->delete($company->logo);
            }
            $validated['logo'] = $request->file('logo')->store('companies/logos', 'public');
        }

        $company->update($validated);

        return redirect()->route('admin.companies.index')->with('success', 'Empresa actualizada correctamente.');
    }

    /**
     * Activar / Desactivar empresa
     */
    public function toggleStatus(Company $company)
    {
        $company->active = !$company->active;
        $company->status = $company->active ? 'active' : 'suspended';
        $company->save();

        return back()->with('success', 'Estado de la empresa actualizado correctamente.');
    }

    /**
     * Eliminar empresa
     */
    public function destroy(Company $company)
    {
        if ($company->logo && Storage::disk('public')->exists($company->logo)) {
            Storage::disk('public')->delete($company->logo);
        }

        $company->delete();

        return redirect()->route('admin.companies.index')->with('success', 'Empresa eliminada correctamente.');
    }
}

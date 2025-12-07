<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use App\Models\EmployeeType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeCatalogController extends Controller
{
    public function storeType(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:150'],
            'catalog' => ['required', 'in:employee,document'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('open_employee_catalogs', true);
        }

        $data = $validator->validated();
        $companyId = $request->user()->company_id;

        $model = $data['catalog'] === 'employee' ? EmployeeType::class : DocumentType::class;

        $model::updateOrCreate(
            ['company_id' => $companyId, 'name' => $data['name']],
            ['name' => $data['name']]
        );

        return back()->with('status', 'Registro guardado.')->with('open_employee_catalogs', true);
    }

    public function updateType(Request $request, string $catalog, int $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:150'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('open_employee_catalogs', true);
        }

        $model = $catalog === 'employee' ? EmployeeType::class : DocumentType::class;
        $record = $model::findOrFail($id);
        abort_unless($record->company_id === $request->user()->company_id, 403);

        $record->update(['name' => $request->input('name')]);

        return back()->with('status', 'Registro actualizado.')->with('open_employee_catalogs', true);
    }

    public function destroyType(Request $request, string $catalog, int $id): RedirectResponse
    {
        $model = $catalog === 'employee' ? EmployeeType::class : DocumentType::class;
        $record = $model::findOrFail($id);
        abort_unless($record->company_id === $request->user()->company_id, 403);

        $record->delete();

        return back()->with('status', 'Registro eliminado.')->with('open_employee_catalogs', true);
    }
}

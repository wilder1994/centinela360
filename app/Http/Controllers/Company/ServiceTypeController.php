<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceTypeController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:150'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('open_service_types', true);
        }

        $data = $validator->validated();
        $companyId = $request->user()->company_id;

        ServiceType::updateOrCreate(
            ['company_id' => $companyId, 'name' => $data['name']],
            ['name' => $data['name']]
        );

        return back()->with('status', 'Tipo de servicio creado/actualizado.')->with('open_service_types', true);
    }

    public function update(Request $request, ServiceType $serviceType): RedirectResponse
    {
        abort_unless($serviceType->company_id === $request->user()->company_id, 403);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:150'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('open_service_types', true);
        }

        $data = $validator->validated();
        $serviceType->update($data);

        return back()->with('status', 'Tipo de servicio actualizado.')->with('open_service_types', true);
    }

    public function destroy(Request $request, ServiceType $serviceType): RedirectResponse
    {
        abort_unless($serviceType->company_id === $request->user()->company_id, 403);
        $serviceType->delete();

        return back()->with('status', 'Tipo de servicio eliminado.')->with('open_service_types', true);
    }
}

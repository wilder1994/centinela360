<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Client;
use App\Models\Employee;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin Empresa']);
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $companyId = $request->user()->company_id;

        $employees = Employee::query()
            ->with('client')
            ->forCompany($companyId)
            ->search($search)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('company.employees.index', [
            'employees' => $employees,
            'search' => $search,
        ]);
    }

    public function create(Request $request): View
    {
        return view('company.employees.create', $this->formOptions($request));
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $companyId = $request->user()->company_id;
        $data = $request->validated();

        $photoPath = $request->file('photo')
            ? $request->file('photo')->store('employees', 'public')
            : null;

        Employee::create([
            'company_id' => $companyId,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'position' => $data['position'],
            'document_type' => $data['document_type'],
            'document_number' => $data['document_number'],
            'rh' => $data['rh'],
            'address' => $data['address'],
            'birth_date' => $data['birth_date'],
            'start_date' => $data['start_date'],
            'badge_expires_at' => $data['badge_expires_at'],
            'photo_path' => $photoPath,
            'client_id' => $data['client_id'],
            'service_type' => $data['service_type'],
            'status' => $data['status'],
            'emergency_contact_name' => $data['emergency_contact_name'],
            'emergency_contact_phone' => $data['emergency_contact_phone'],
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()
            ->route('company.employees.index')
            ->with('status', 'Empleado creado correctamente.');
    }

    public function edit(Request $request, Employee $employee): View
    {
        $this->authorizeEmployee($request, $employee);

        return view('company.employees.edit', array_merge([
            'employee' => $employee,
        ], $this->formOptions($request)));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $this->authorizeEmployee($request, $employee);
        $data = $request->validated();

        $payload = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'position' => $data['position'],
            'document_type' => $data['document_type'],
            'document_number' => $data['document_number'],
            'rh' => $data['rh'],
            'address' => $data['address'],
            'birth_date' => $data['birth_date'],
            'start_date' => $data['start_date'],
            'badge_expires_at' => $data['badge_expires_at'],
            'client_id' => $data['client_id'],
            'service_type' => $data['service_type'],
            'status' => $data['status'],
            'emergency_contact_name' => $data['emergency_contact_name'],
            'emergency_contact_phone' => $data['emergency_contact_phone'],
            'notes' => $data['notes'] ?? null,
        ];

        if ($request->hasFile('photo')) {
            if ($employee->photo_path) {
                Storage::disk('public')->delete($employee->photo_path);
            }

            $payload['photo_path'] = $request->file('photo')->store('employees', 'public');
        }

        $employee->update($payload);

        return redirect()
            ->route('company.employees.index')
            ->with('status', 'Empleado actualizado correctamente.');
    }

    public function destroy(Request $request, Employee $employee): RedirectResponse
    {
        $this->authorizeEmployee($request, $employee);

        if ($employee->photo_path) {
            Storage::disk('public')->delete($employee->photo_path);
        }

        $employee->delete();

        return redirect()
            ->route('company.employees.index')
            ->with('status', 'Empleado eliminado correctamente.');
    }

    private function authorizeEmployee(Request $request, Employee $employee): void
    {
        abort_unless($employee->company_id === $request->user()->company_id, 403);
    }

    private function formOptions(Request $request): array
    {
        $companyId = $request->user()->company_id;

        $clients = Client::query()
            ->with('services')
            ->forCompany($companyId)
            ->orderBy('business_name')
            ->get();

        $clientServicesMap = $clients->mapWithKeys(fn (Client $client) => [
            $client->id => $client->services->pluck('service_type'),
        ]);

        $serviceTypesOptions = $clients
            ->flatMap(fn (Client $client) => $client->services->pluck('service_type'))
            ->merge(['Portería', 'Ronda', 'Control de acceso', 'Supervisión'])
            ->unique()
            ->values();

        return [
            'clients' => $clients,
            'clientServicesMap' => $clientServicesMap,
            'serviceTypesOptions' => $serviceTypesOptions,
            'documentTypes' => ['CC', 'CE', 'PAS'],
            'rhOptions' => ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'],
            'positions' => ['Guarda', 'Administrativo'],
            'statusOptions' => ['Activo', 'En vacaciones', 'Incapacitado', 'Desprogramado', 'Calamidad', 'Despedido'],
        ];
    }
}

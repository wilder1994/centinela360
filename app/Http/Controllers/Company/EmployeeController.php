<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Client;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\EmployeeType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
            ->with(['client', 'activityNotes.user'])
            ->forCompany($companyId)
            ->withoutArchived()
            ->search($search)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('company.employees.index', [
            'employees' => $employees,
            'search' => $search,
            'employeeTypes' => EmployeeType::where('company_id', $companyId)->orderBy('name')->get(),
            'documentTypesCatalog' => DocumentType::where('company_id', $companyId)->orderBy('name')->get(),
        ]);
    }

    public function archived(Request $request): View
    {
        $search = $request->string('search')->toString();
        $companyId = $request->user()->company_id;

        $employees = Employee::query()
            ->with(['client', 'activityNotes.user'])
            ->forCompany($companyId)
            ->onlyArchived()
            ->search($search)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('company.employees.archived', [
            'employees' => $employees,
            'search' => $search,
            'employeeTypes' => EmployeeType::where('company_id', $companyId)->orderBy('name')->get(),
            'documentTypesCatalog' => DocumentType::where('company_id', $companyId)->orderBy('name')->get(),
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

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('employees/photos', 'public');
        }

        Employee::create([
            'company_id' => $companyId,
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'] ?? null,
            'phone'      => $data['phone'],
            'position'   => $data['position'],
            'document_type'   => $data['document_type'],
            'document_number' => $data['document_number'],
            'rh'              => $data['rh'],
            'address'         => $data['address'],
            'birth_date'      => $data['birth_date'],
            'start_date'      => $data['start_date'],
            'badge_expires_at' => $data['badge_expires_at'],
            'client_id'       => $data['client_id'],
            'service_type'    => $data['service_type'],
            'status'          => $data['status'],
            'emergency_contact_name'  => $data['emergency_contact_name'],
            'emergency_contact_phone' => $data['emergency_contact_phone'],
            'notes'           => $data['notes'] ?? null,
            'photo_path'      => $data['photo_path'] ?? null,
        ]);
        Employee::where('company_id', $companyId)
            ->latest('id')
            ->first()
            ?->activityNotes()
            ->create([
                'user_id' => $request->user()->id,
                'body' => 'Empleado creado.',
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

        if ($request->hasFile('photo')) {
            if ($employee->photo_path && Storage::disk('public')->exists($employee->photo_path)) {
                Storage::disk('public')->delete($employee->photo_path);
            }

            $data['photo_path'] = $request->file('photo')->store('employees/photos', 'public');
        }

        $employee->update([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'] ?? null,
            'phone'      => $data['phone'],
            'position'   => $data['position'],
            'document_type'   => $data['document_type'],
            'document_number' => $data['document_number'],
            'rh'              => $data['rh'],
            'address'         => $data['address'],
            'birth_date'      => $data['birth_date'],
            'start_date'      => $data['start_date'],
            'badge_expires_at' => $data['badge_expires_at'],
            'client_id'       => $data['client_id'],
            'service_type'    => $data['service_type'],
            'status'          => $data['status'],
            'emergency_contact_name'  => $data['emergency_contact_name'],
            'emergency_contact_phone' => $data['emergency_contact_phone'],
            'notes'           => $data['notes'] ?? null,
            'photo_path'      => $data['photo_path'] ?? $employee->photo_path,
        ]);
        $employee->activityNotes()->create([
            'user_id' => $request->user()->id,
            'body' => 'Empleado actualizado.',
        ]);

        return redirect()
            ->route('company.employees.index')
            ->with('status', 'Empleado actualizado correctamente.');
    }

    public function destroy(Request $request, Employee $employee): RedirectResponse
    {
        $this->authorizeEmployee($request, $employee);

        $data = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        $employee->update(['archived_at' => Carbon::now()]);
        $employee->activityNotes()->create([
            'user_id' => $request->user()->id,
            'body' => 'Archivado: ' . $data['comment'],
        ]);

        return redirect()
            ->route('company.employees.index')
            ->with('status', 'Empleado archivado correctamente.');
    }

    public function unarchive(Request $request, Employee $employee): RedirectResponse
    {
        $this->authorizeEmployee($request, $employee);

        $data = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        $employee->update(['archived_at' => null]);
        $employee->activityNotes()->create([
            'user_id' => $request->user()->id,
            'body' => 'Desarchivado: ' . $data['comment'],
        ]);

        return back()->with('status', 'Empleado desarchivado correctamente.');
    }

    public function storeNote(Request $request, Employee $employee): RedirectResponse
    {
        $this->authorizeEmployee($request, $employee);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $employee->activityNotes()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        return back()->with('status', 'Nota registrada.');
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
            ->unique()
            ->values();

        $positions = EmployeeType::where('company_id', $companyId)->orderBy('name')->pluck('name')->toArray();
        $documentTypes = DocumentType::where('company_id', $companyId)->orderBy('name')->pluck('name')->toArray();

        return [
            'clients' => $clients,
            'clientServicesMap' => $clientServicesMap,
            'serviceTypesOptions' => $serviceTypesOptions,
            'documentTypes' => $documentTypes,
            'rhOptions' => ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'],
            'positions' => $positions,
            'statusOptions' => ['Activo', 'En vacaciones', 'Incapacitado', 'Desprogramado', 'Calamidad', 'Despedido'],
        ];
    }
}

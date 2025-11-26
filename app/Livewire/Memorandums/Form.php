<?php

namespace App\Livewire\Memorandums;

use App\Enums\MemorandumStatus;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Memorandum;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Form extends Component
{
    public ?Memorandum $memorandum = null;
    public bool $isEdit = false;

    // 🔹 Campos “clásicos” del modelo
    public string $subject = '';
    public ?string $issued_at = null; // si luego quieres usar datetime-local
    public string $body = '';         // cuerpo completo (lo armamos desde los campos de abajo)

    // 🔹 Campos de la UI tipo P3 (solo se guardan “empaquetados” en body)
    public string $puesto = '';
    public string $cargo = '';
    public string $nombre = '';
    public string $cedula = '';
    public ?int $clientId = null;         // cliente asociado al puesto
    public ?int $employeeId = null;       // empleado seleccionado
    public ?int $responsable = null;      // id de usuario responsable
    public string $prioridad = 'media';   // urgente / alta / media / baja
    public string $descripcion = '';      // texto libre principal

    public string $selectedClientName = '';
    public string $selectedEmployeeName = '';

    public function mount(?Memorandum $memorandum = null): void
    {
        $this->memorandum = $memorandum;
        $this->isEdit = $memorandum && $memorandum->exists;

        if ($this->isEdit) {
            $this->memorandum->loadMissing(['employee.client']);

            // Mapeo básico para edición (no intentamos parsear campos, solo llenamos lo mínimo)
            $this->subject     = $this->memorandum->subject;
            $this->descripcion = $this->memorandum->body;
            $this->issued_at   = optional($this->memorandum->issued_at)?->format('Y-m-d\TH:i');

            // Si el memorando ya tenía empleado asociado, rellenamos los campos derivados
            if ($this->memorandum->employee) {
                $employee            = $this->memorandum->employee;
                $this->employeeId    = $employee->id;
                $this->nombre        = $employee->full_name;
                $this->cedula        = $employee->document_number ?? '';
                $this->cargo         = $employee->service_type ?? $employee->position ?? '';
                $this->clientId      = $employee->client_id;
                $this->puesto        = $employee->client?->business_name ?? '';
            }
        } else {

            // Valores por defecto
            $this->prioridad = 'media';
        }
    }

    protected function rules(): array
    {
        $companyId = $this->companyId();

        return [
            'clientId'    => [
                'required',
                Rule::exists('clients', 'id')->where(fn ($q) => $q->where('company_id', $companyId)),
            ],
            'puesto'      => ['required', 'string', 'max:255'],
            'subject'     => ['required', 'string', 'max:255'],
            'cargo'       => ['required', 'string', 'max:255'],
            'nombre'      => ['required', 'string', 'max:255'],
            'cedula'      => ['required', 'string', 'max:50'],
            'employeeId'  => [
                'required',
                Rule::exists('employees', 'id')->where(function ($q) use ($companyId) {
                    $q->where('company_id', $companyId);

                    if ($this->clientId) {
                        $q->where('client_id', $this->clientId);
                    }
                }),
            ],
            'responsable' => [
                'nullable',
                Rule::exists('users', 'id')->where(fn ($q) => $q->where('company_id', $companyId)),
            ],
            'descripcion' => ['required', 'string'],
            'prioridad'   => ['required', Rule::in(['urgente', 'alta', 'media', 'baja'])],
            'issued_at'   => ['nullable', 'date'],
        ];
    }

    public function save()
    {
        $this->validate();

        $companyId = $this->companyId();
        $user = auth()->user();

        if (! $user) {
            abort(403, 'No se encontró un usuario autenticado.');
        }

        $client = Client::forCompany($companyId)->findOrFail($this->clientId);
        $employee = Employee::forCompany($companyId)
            ->when($client->id, fn ($query) => $query->where('client_id', $client->id))
            ->findOrFail($this->employeeId);

        // Buscamos el responsable (usuario) solo para usar su nombre en el texto
        $responsableUser = $this->responsable
            ? User::query()->where('company_id', $companyId)->find($this->responsable)
            : null;

        // 🔹 Armamos el cuerpo completo con todos los campos “estilo P3”
        $body = "Puesto: {$client->business_name}\n"
            . "Cargo: {$this->cargo}\n"
            . "Nombre: {$this->nombre}\n"
            . "Cédula: {$this->cedula}\n"
            . "Responsable: " . ($responsableUser?->name ?? 'Sin responsable asignado') . "\n"
            . "Prioridad: " . ucfirst($this->prioridad) . "\n\n"
            . "Descripción:\n{$this->descripcion}";

        if ($this->isEdit) {
            $memorandum = $this->memorandum;

            $memorandum->subject = $this->subject;
            $memorandum->body    = $body;
            $memorandum->employee_id = $employee->id;
            $memorandum->issued_at = $this->issued_at
                ? \Carbon\Carbon::parse($this->issued_at)
                : $memorandum->issued_at;

            $memorandum->save();

            session()->flash('status', 'Memorándum actualizado correctamente.');
        } else {
            $memorandum = new Memorandum();

            $memorandum->company_id = $user->company_id;
            $memorandum->user_id    = $user->id; // autor
            $memorandum->employee_id = $employee->id;

            $memorandum->subject = $this->subject;
            $memorandum->body    = $body;
            $memorandum->status  = MemorandumStatus::DRAFT;
            $memorandum->issued_at = $this->issued_at
                ? \Carbon\Carbon::parse($this->issued_at)
                : null;

            $memorandum->save();

            session()->flash('status', 'Memorándum creado correctamente.');
        }

        return $this->redirectRoute('company.memorandums.show', $memorandum);
    }

    public function render()
    {
        $companyId = $this->companyId();

        $clients = Client::query()
            ->forCompany($companyId)
            ->search($this->puesto)
            ->orderBy('business_name')
            ->limit(10)
            ->get();

        // Si más adelante quieres usar empleados, aquí los tienes disponibles:
        $employees = Employee::query()
            ->where('company_id', $companyId)
            ->when($this->clientId, fn ($query) => $query->where('client_id', $this->clientId))
            ->search($this->nombre)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->limit(10)
            ->get();

        // Responsables (usuarios de la empresa)
        $usuarios = User::query()
            ->where('company_id', $companyId)
            ->orderBy('name')
            ->get();

        return view('livewire.memorandums.form', [
            'clients'    => $clients,
            'employees'  => $employees,
            'usuarios'   => $usuarios,
        ]);
    }

    public function selectClient(int $clientId): void
    {
        $companyId = $this->companyId();
        $client = Client::forCompany($companyId)->find($clientId);

        if (! $client) {
            return;
        }

        $this->clientId = $client->id;
        $this->puesto   = $client->business_name;

        $this->resetEmployeeSelection();
    }

    public function selectEmployee(int $employeeId): void
    {
        $companyId = $this->companyId();

        $employee = Employee::forCompany($companyId)
            ->when($this->clientId, fn ($query) => $query->where('client_id', $this->clientId))
            ->find($employeeId);

        if (! $employee) {
            return;
        }

        $this->employeeId = $employee->id;
        $this->nombre     = $employee->full_name;
        $this->cedula     = $employee->document_number ?? '';
        $this->cargo      = $employee->service_type ?? $employee->position ?? '';
    }

    public function updatedPuesto(): void
    {
        $this->clientId = null;
        $this->resetEmployeeSelection();
    }

    public function updatedNombre(): void
    {
        $this->resetEmployeeSelection();
    }

    private function resetEmployeeSelection(): void
    {
        $this->employeeId = null;
        $this->nombre     = '';
        $this->cedula     = '';
        $this->cargo      = '';
    }

    private function companyId(): int
    {
        $companyId = auth()->user()?->company_id;

        if (! $companyId) {
            abort(403, 'No se encontró una empresa asociada al usuario.');
        }

        return $companyId;
    }
}

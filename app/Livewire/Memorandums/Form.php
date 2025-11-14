<?php

namespace App\Livewire\Memorandums;

use App\Enums\MemorandumStatus;
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
    public ?int $responsable = null;      // id de usuario responsable
    public string $prioridad = 'media';   // urgente / alta / media / baja
    public string $descripcion = '';      // texto libre principal

    public function mount(?Memorandum $memorandum = null): void
    {
        $this->memorandum = $memorandum;
        $this->isEdit = $memorandum && $memorandum->exists;

        if ($this->isEdit) {

            // Mapeo básico para edición (no intentamos parsear campos, solo llenamos lo mínimo)
            $this->subject     = $this->memorandum->subject;
            $this->descripcion = $this->memorandum->body;
            $this->issued_at   = optional($this->memorandum->issued_at)?->format('Y-m-d\TH:i');
        } else {
            
            // Valores por defecto
            $this->prioridad = 'media';
        }
    }

    protected function rules(): array
    {
        return [
            'puesto'      => ['required', 'string', 'max:255'],
            'subject'     => ['required', 'string', 'max:255'],
            'cargo'       => ['required', 'string', 'max:255'],
            'nombre'      => ['required', 'string', 'max:255'],
            'cedula'      => ['required', 'string', 'max:50'],
            'responsable' => ['nullable', Rule::exists('users', 'id')],
            'descripcion' => ['required', 'string'],
            'prioridad'   => ['required', Rule::in(['urgente', 'alta', 'media', 'baja'])],
            'issued_at'   => ['nullable', 'date'],
        ];
    }

    public function save()
    {
        $this->validate();

        $user = auth()->user();
        if (! $user || ! $user->company_id) {
            abort(403, 'No se encontró una empresa asociada al usuario.');
        }

        // Buscamos el responsable (usuario) solo para usar su nombre en el texto
        $responsableUser = $this->responsable
            ? User::find($this->responsable)
            : null;

        // 🔹 Armamos el cuerpo completo con todos los campos “estilo P3”
        $body = "Puesto: {$this->puesto}\n"
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
            $memorandum->issued_at = $this->issued_at
                ? \Carbon\Carbon::parse($this->issued_at)
                : $memorandum->issued_at;

            $memorandum->save();

            session()->flash('status', 'Memorándum actualizado correctamente.');
        } else {
            $memorandum = new Memorandum();

            $memorandum->company_id = $user->company_id;
            $memorandum->user_id    = $user->id; // autor
            $memorandum->employee_id = null;     // por ahora no lo usamos en este formulario

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
        $companyId = auth()->user()->company_id;

        // Si más adelante quieres usar empleados, aquí los tienes disponibles:
        $employees = Employee::query()
            ->where('company_id', $companyId)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        // Responsables (usuarios de la empresa)
        $usuarios = User::query()
            ->where('company_id', $companyId)
            ->orderBy('name')
            ->get();

        return view('livewire.memorandums.form', [
            'employees' => $employees,
            'usuarios'  => $usuarios,
        ]);
    }
}

<?php

namespace App\Http\Livewire\Company\Memorandums;

use App\Models\Memorandum;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Board extends Component
{
    public array $estadosVisibles = [];
    public string $tituloTabla = 'Memorandos';
    public string $mensajeVacio = 'No hay memorandos registrados.';
    public string $search = '';

    public $ticketsPlanos = [];
    public $conteos = [];

    public bool $mostrarModal = false;
    public bool $mostrarModalDetalles = false;
    public ?Memorandum $ticketDetalle = null;
    public ?int $ticketActualId = null;

    public bool $cambioEstado = false;
    public string $nuevoEstado = '';
    public string $comentario = '';
    public ?int $responsable = null;
    public $usuarios = [];
    public string $finalDecision = '';

    public function mount(array $estadosVisibles = [], string $tituloTabla = 'Memorandos', string $mensajeVacio = 'No hay memorandos registrados.')
    {
        $this->estadosVisibles = $estadosVisibles;
        $this->tituloTabla = $tituloTabla;
        $this->mensajeVacio = $mensajeVacio;
        $companyId = Auth::user()->company_id;
        $this->usuarios = User::where('active', true)
            ->where('company_id', $companyId)
            ->orderBy('name')
            ->get();

        $this->cargarDatos();
    }

    public function updatedSearch()
    {
        $this->cargarDatos();
    }

    public function filtrar()
    {
        $this->cargarDatos();
    }

    protected function cargarDatos(): void
    {
        $companyId = Auth::user()->company_id;

        $query = Memorandum::query()
            ->with(['author', 'assignedTo'])
            ->where('company_id', $companyId)
            ->orderByDesc('created_at');

        if (! empty($this->estadosVisibles)) {
            $dbEstados = array_map([$this, 'mapEstadoEntrada'], $this->estadosVisibles);
            $query->whereIn('estado', $dbEstados);
        }

        if ($this->search !== '') {
            $term = '%' . $this->search . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('body', 'like', $term)
                    ->orWhere('prioridad', 'like', $term)
                    ->orWhereHas('author', fn ($a) => $a->where('name', 'like', $term))
                    ->orWhereHas('assignedTo', fn ($a) => $a->where('name', 'like', $term));
            });
        }

        $memorandums = $query->get();

        $this->ticketsPlanos = $memorandums->map(function (Memorandum $memo) {
            $obj = (object) [
                'id' => $memo->id,
                'created_at' => $memo->created_at,
                'creador' => $memo->author,
                'puesto' => $memo->puesto,
                'titulo' => $memo->title,
                'descripcion' => $memo->body,
                'asignado' => $memo->assignedTo,
                'prioridad' => $memo->prioridad,
                'estado' => $memo->estado,
                'final_status' => $memo->final_status ?? null,
                'nombre_guarda' => $memo->assignedTo?->name,
                'cedula_guarda' => '',
                'cargo' => '',
            ];

            return $obj;
        })->all();

        $this->conteos = [
            'pendiente'   => Memorandum::where('company_id', $companyId)->where('estado', 'pending')->count(),
            'en_proceso'  => Memorandum::where('company_id', $companyId)->where('estado', 'en_proceso')->count(),
            'finalizado'  => Memorandum::where('company_id', $companyId)->where('estado', 'finalizado')->count(),
        ];
    }

    protected function mapEstadoEntrada(string $estado): string
    {
        return match ($estado) {
            'pendiente' => 'pending',
            default => $estado,
        };
    }

    public function verDetalles($id): void
    {
        $companyId = Auth::user()->company_id;
        $this->ticketDetalle = Memorandum::with(['author', 'assignedTo', 'logs.user'])
            ->where('company_id', $companyId)
            ->findOrFail($id);
        if ($this->ticketDetalle) {
            $this->ticketDetalle->titulo = $this->ticketDetalle->title;
            $this->ticketDetalle->descripcion = $this->ticketDetalle->body;
            $this->ticketDetalle->puesto = $this->ticketDetalle->puesto ?? '';
            $this->ticketDetalle->nombre_guarda = $this->ticketDetalle->assignedTo?->name;
            $this->ticketDetalle->cedula_guarda = $this->ticketDetalle->cedula_guarda ?? '';
            $this->ticketDetalle->cargo = $this->ticketDetalle->cargo ?? '';

            foreach ($this->ticketDetalle->logs as $log) {
                $log->setRelation('usuario', $log->user);
            }
        }
        $this->mostrarModalDetalles = true;
    }

    public function confirmarCambioEstado($id, $estado, $cambio): void
    {
        $this->ticketActualId = $id;
        $this->nuevoEstado = $estado;
        $this->cambioEstado = (bool) $cambio;
        $this->comentario = '';
        $this->responsable = null;
        $this->finalDecision = '';
        $this->mostrarModal = true;
    }

    public function guardarCambioEstado(): void
    {
        $this->validate([
            'comentario' => ['required', 'string', 'min:3'],
            'responsable' => ['required', 'integer', 'exists:users,id'],
        ], [
            'comentario.required' => 'El comentario es obligatorio.',
            'responsable.required' => 'Debe asignar un responsable.',
        ]);

        $companyId = Auth::user()->company_id;
        $memo = Memorandum::with('logs')
            ->where('company_id', $companyId)
            ->find($this->ticketActualId);
        if (! $memo) {
            $this->resetModal();
            return;
        }

        $estadoAnterior = $memo->estado;
        // Primer comentario en pendiente pasa a en_proceso automáticamente
        $estadoDestino = $estadoAnterior === 'pending'
            ? 'en_proceso'
            : ($this->cambioEstado ? $this->nuevoEstado : $estadoAnterior);

        $memo->assigned_to = $this->responsable;
        $memo->estado = $estadoDestino;
        if ($estadoDestino !== 'finalizado') {
            $memo->final_status = null;
        }
        $memo->save();

        $memo->logs()->create([
            'user_id' => auth()->id(),
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $estadoDestino,
            'comentario' => $this->comentario,
        ]);

        $this->resetModal();
        $this->cargarDatos();
    }

    public function finalizarTicketDesdeModal(): void
    {
        $this->validate([
            'comentario' => ['required', 'string', 'min:3'],
            'finalDecision' => ['required', 'in:aprobado,negado'],
        ], [
            'comentario.required' => 'El comentario es obligatorio.',
            'finalDecision.required' => 'Debe indicar si fue aprobado o negado.',
        ]);

        $companyId = Auth::user()->company_id;
        $memo = Memorandum::with('logs')
            ->where('company_id', $companyId)
            ->find($this->ticketActualId);
        if (! $memo) {
            $this->resetModal();
            return;
        }

        $estadoAnterior = $memo->estado;
        $estadoDestino = 'finalizado';

        $memo->assigned_to = $this->responsable ?: Auth::id();
        $memo->estado = $estadoDestino;
        $memo->final_status = $this->finalDecision;
        $memo->save();

        $memo->logs()->create([
            'user_id' => auth()->id(),
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $estadoDestino,
            'comentario' => trim($this->comentario . ' (' . ucfirst($this->finalDecision) . ')'),
        ]);

        $this->resetModal();
        $this->cargarDatos();
    }

    private function resetModal(): void
    {
        $this->mostrarModal = false;
        $this->comentario = '';
        $this->responsable = null;
        $this->ticketActualId = null;
        $this->nuevoEstado = '';
        $this->cambioEstado = false;
        $this->finalDecision = '';
    }

    public function render()
    {
        return view('livewire.company.memorandums.board');
    }
}

<?php

namespace App\Http\Livewire\Company\Memorandums;

use App\Models\Memorandum;
use App\Models\User;
use Livewire\Component;

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

    public bool $cambioEstado = false;
    public string $nuevoEstado = '';
    public string $comentario = '';
    public ?int $responsable = null;
    public $usuarios = [];

    public function mount(array $estadosVisibles = [], string $tituloTabla = 'Memorandos', string $mensajeVacio = 'No hay memorandos registrados.')
    {
        $this->estadosVisibles = $estadosVisibles;
        $this->tituloTabla = $tituloTabla;
        $this->mensajeVacio = $mensajeVacio;
        $this->usuarios = User::where('active', true)->orderBy('name')->get();

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
        $query = Memorandum::query()
            ->with(['author', 'assignedTo'])
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
                'nombre_guarda' => $memo->assignedTo?->name,
                'cedula_guarda' => '',
                'cargo' => '',
            ];

            return $obj;
        })->all();

        $this->conteos = [
            'pendiente'   => Memorandum::where('estado', 'pending')->count(),
            'en_proceso'  => Memorandum::where('estado', 'en_proceso')->count(),
            'finalizado'  => Memorandum::where('estado', 'finalizado')->count(),
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
        $this->ticketDetalle = Memorandum::with(['author', 'assignedTo', 'logs.user'])->findOrFail($id);
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
        $this->nuevoEstado = $estado;
        $this->cambioEstado = (bool) $cambio;
        $this->comentario = '';
        $this->responsable = null;
        $this->mostrarModal = true;
    }

    public function guardarCambioEstado(): void
    {
        $this->mostrarModal = false;
        $this->comentario = '';
        $this->responsable = null;
    }

    public function finalizarTicketDesdeModal(): void
    {
        $this->mostrarModal = false;
        $this->comentario = '';
        $this->responsable = null;
    }

    public function render()
    {
        return view('livewire.company.memorandums.board');
    }
}

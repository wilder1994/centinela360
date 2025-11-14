<?php

namespace App\Livewire\Memorandums;

use App\Enums\MemorandumStatus;
use App\Models\Memorandum;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.company')]
class Board extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    /** Texto del buscador */
    public string $search = '';

    /** Estado filtrado actualmente (draft, in_review, acknowledged, archived o null) */
    public ?string $filtroEstado = null;

    /** Conteos por estado para las tarjetas */
    public array $conteos = [
        'draft'        => 0,
        'in_review'    => 0,
        'acknowledged' => 0,
        'archived'     => 0,
    ];

    /** Título de la tabla (se puede sobreescribir desde la ruta si quieres) */
    public string $tituloTabla = 'Listado de memorándums';

    /** Mensaje cuando no hay registros */
    public ?string $mensajeVacio = null;

    /**
     * Puedes pasar parámetros desde la ruta si lo necesitas, por ahora todos son opcionales.
     */
    public function mount(
        ?string $status = null,
        ?string $tituloTabla = null,
        ?string $mensajeVacio = null,
    ): void {
        $this->filtroEstado = $status;

        if ($tituloTabla) {
            $this->tituloTabla = $tituloTabla;
        }

        if ($mensajeVacio) {
            $this->mensajeVacio = $mensajeVacio;
        }

        $this->actualizarConteos();
    }

    /**
     * Cada vez que cambia el texto de búsqueda, reiniciamos la paginación.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Hook para el input del buscador (desde la vista).
     */
    public function filtrar(): void
    {
        $this->resetPage();
    }

    /**
     * Al hacer clic en una tarjeta de estado.
     * Si ya estaba seleccionado, se deselecciona (toggle).
     */
    public function filtrarPorEstado(string $estado): void
    {
        $this->filtroEstado = $this->filtroEstado === $estado ? null : $estado;
        $this->resetPage();
    }

    /**
     * Construye el query base de memorándums de la empresa actual con filtros.
     */
    protected function getMemorandumsQuery()
    {
        $user = auth()->user();

        if (! $user || ! $user->company_id) {
            // Si por alguna razón no hay empresa, devolvemos un query vacío.
            return Memorandum::query()->whereRaw('1 = 0');
        }

        // Política general (si tienes policy para Memorandum)
        try {
            $this->authorize('viewAny', Memorandum::class);
        } catch (\Throwable $e) {
            // Si aún no tienes policy puedes comentar esta línea,
            // o simplemente ignorar la excepción.
        }

        $query = Memorandum::query()
            ->forCompany($user->company_id)
            ->with(['author', 'employee'])
            ->orderByDesc('issued_at')
            ->orderByDesc('created_at');

        // Filtro de estado
        if ($this->filtroEstado) {
            $query->where('status', $this->filtroEstado);
        }

        // Filtro de búsqueda
        if (trim($this->search) !== '') {
            $term = trim($this->search);

            $query->where(function ($q) use ($term) {
                $q->where('subject', 'like', "%{$term}%")
                    ->orWhere('body', 'like', "%{$term}%")
                    ->orWhereHas('employee', function ($q2) use ($term) {
                        $q2->where('first_name', 'like', "%{$term}%")
                            ->orWhere('last_name', 'like', "%{$term}%")
                            ->orWhere('position', 'like', "%{$term}%");
                    })
                    ->orWhereHas('author', function ($q2) use ($term) {
                        $q2->where('name', 'like', "%{$term}%");
                    });
            });
        }

        return $query;
    }

    /**
     * Recalcula los conteos por estado para las tarjetas.
     */
    protected function actualizarConteos(): void
    {
        $user = auth()->user();

        if (! $user || ! $user->company_id) {
            $this->conteos = [
                'draft'        => 0,
                'in_review'    => 0,
                'acknowledged' => 0,
                'archived'     => 0,
            ];
            return;
        }

        $base = Memorandum::query()->forCompany($user->company_id);

        $this->conteos = [
            'draft'        => (clone $base)->where('status', MemorandumStatus::DRAFT->value)->count(),
            'in_review'    => (clone $base)->where('status', MemorandumStatus::IN_REVIEW->value)->count(),
            'acknowledged' => (clone $base)->where('status', MemorandumStatus::ACKNOWLEDGED->value)->count(),
            'archived'     => (clone $base)->where('status', MemorandumStatus::ARCHIVED->value)->count(),
        ];
    }

    public function render()
    {
        $query = $this->getMemorandumsQuery();

        // Recalculamos conteos para que estén frescos.
        $this->actualizarConteos();

        $memorandums = $query->paginate(20);

        return view('livewire.memorandums.board', [
            'memorandumsPlanos' => $memorandums,
            'tituloTabla'       => $this->tituloTabla,
            'mensajeVacio'      => $this->mensajeVacio,
            'conteos'           => $this->conteos,
            'filtroEstado'      => $this->filtroEstado,
        ]);
    }
}

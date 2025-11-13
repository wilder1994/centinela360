<?php

namespace App\Livewire\Memorandums;

use App\Enums\MemorandumStatus;
use App\Models\Employee;
use App\Models\Memorandum;
use App\Models\MemorandumStatusHistory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Livewire\Component;

class Board extends Component
{
    use AuthorizesRequests;

    public string $search = '';
    public ?int $employeeId = null;

    /**
     * Devuelve el ID de la empresa actual del usuario autenticado.
     */
    protected function currentCompanyId(): int
    {
        $user = auth()->user();

        if (! $user || ! $user->company_id) {
            abort(403, 'No se encontró una empresa asociada al usuario.');
        }

        return (int) $user->company_id;
    }

    /**
     * Consulta base de memorandos para la empresa actual.
     */
    protected function baseQuery()
    {
        $companyId = $this->currentCompanyId();

        $query = Memorandum::query()
            ->forCompany($companyId)
            ->with(['employee', 'author'])
            ->orderByDesc('issued_at')
            ->orderByDesc('created_at');

        if (filled($this->search)) {
            $query->search($this->search);
        }

        if ($this->employeeId) {
            $query->where('employee_id', $this->employeeId);
        }

        return $query;
    }

    /**
     * Obtiene la colección de memorandos según filtros actuales.
     *
     * @return \Illuminate\Support\Collection<int, \App\Models\Memorandum>
     */
    protected function memorandums(): Collection
    {
        return $this->baseQuery()->get();
    }

    /**
     * Agrupa los memorandos por estado usando las constantes del modelo.
     *
     * @param  \Illuminate\Support\Collection<int, Memorandum>  $memorandums
     * @return array<string, \Illuminate\Support\Collection>
     */
    protected function groupedByStatus(Collection $memorandums): array
    {
        $columns = [];

        foreach (Memorandum::STATUSES as $status) {
            $columns[$status] = $memorandums->filter(function (Memorandum $memorandum) use ($status) {
                $current = $memorandum->status instanceof MemorandumStatus
                    ? $memorandum->status->value
                    : $memorandum->status;

                return $current === $status;
            });
        }

        return $columns;
    }

    /**
     * Construye estadísticas simples por estado.
     *
     * @param  \Illuminate\Support\Collection<int, Memorandum>  $memorandums
     * @return array<string, int>
     */
    protected function buildStats(Collection $memorandums): array
    {
        $countsByStatus = $memorandums
            ->groupBy(function (Memorandum $memorandum) {
                return $memorandum->status instanceof MemorandumStatus
                    ? $memorandum->status->value
                    : $memorandum->status;
            })
            ->map
            ->count()
            ->all();

        $total = $memorandums->count();

        return [
            'total'        => $total,
            'draft'        => (int) ($countsByStatus['draft'] ?? 0),
            'in_review'    => (int) ($countsByStatus['in_review'] ?? 0),
            'acknowledged' => (int) ($countsByStatus['acknowledged'] ?? 0),
            'archived'     => (int) ($countsByStatus['archived'] ?? 0),
        ];
    }

    /**
     * Cambia el estado de un memorando y registra el historial.
     */
    public function changeStatus(int $memorandumId, string $newStatus): void
    {
        // Validar que el estado exista en el catálogo
        if (! in_array($newStatus, Memorandum::STATUSES, true)) {
            $this->dispatch('notify', type: 'error', message: 'Estado no válido.');
            return;
        }

        $companyId = $this->currentCompanyId();

        /** @var Memorandum $memorandum */
        $memorandum = Memorandum::query()
            ->forCompany($companyId)
            ->whereKey($memorandumId)
            ->firstOrFail();

        // Política de autorización (MemorandumPolicy@update)
        $this->authorize('update', $memorandum);

        $previousStatus = $memorandum->status instanceof MemorandumStatus
            ? $memorandum->status->value
            : $memorandum->status;

        $statusEnum = MemorandumStatus::from($newStatus);

        // Reglas de negocio:
        // - Si sale de draft, aseguramos issued_at
        // - Si pasa a acknowledged, registramos acknowledged_at
        if ($previousStatus === 'draft'
            && $statusEnum !== MemorandumStatus::DRAFT
            && ! $memorandum->issued_at
        ) {
            $memorandum->issued_at = now();
        }

        if ($statusEnum === MemorandumStatus::ACKNOWLEDGED
            && ! $memorandum->acknowledged_at
        ) {
            $memorandum->acknowledged_at = now();
        }

        $memorandum->status = $statusEnum;
        $memorandum->save();

        // Registrar historial de cambio de estado
        MemorandumStatusHistory::create([
            'memorandum_id'   => $memorandum->id,
            'user_id'         => auth()->id(),
            'previous_status' => $previousStatus,
            'new_status'      => $statusEnum->value,
            'comment'         => null,
        ]);

        $this->dispatch('notify', type: 'success', message: 'Estado actualizado correctamente.');
    }

    public function render(): View
    {
        $companyId = $this->currentCompanyId();

        /** @var \Illuminate\Support\Collection<int, Employee> $employees */
        $employees = Employee::query()
            ->where('company_id', $companyId)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $memorandums = $this->memorandums();
        $columns = $this->groupedByStatus($memorandums);
        $stats = $this->buildStats($memorandums);

        return view('livewire.memorandums.board', [
            'columns'          => $columns,
            'employees'        => $employees,
            'stats'            => $stats,
            'totalMemorandums' => $stats['total'] ?? 0,
        ])->layout('layouts.company');
    }
}

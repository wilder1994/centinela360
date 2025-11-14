<?php

namespace App\Livewire\Memorandums;

use App\Models\Memorandum;
use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;

class Finalized extends Component
{
    use WithPagination;

    public $search = '';
    public $employeeId = '';
    public $status = '';
    public $date_from = '';
    public $date_to = '';
    public $perPage = 12;

    protected $queryString = [
        'search' => ['except' => ''],
        'employeeId' => ['except' => ''],
        'status' => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
    ];

    public function updating($field)
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset([
            'search',
            'employeeId',
            'status',
            'date_from',
            'date_to',
        ]);
    }

    public function render()
    {
        $companyId = auth()->user()->company_id;

        $query = Memorandum::query()
            ->with(['employee', 'author', 'latestStatusHistory'])
            ->forCompany($companyId)
            ->whereIn('status', ['acknowledged', 'archived']);

        // 🔍 Búsqueda por texto
        if ($this->search) {
            $query->where(function ($builder) {
                $builder
                    ->where('subject', 'like', "%{$this->search}%")
                    ->orWhere('body', 'like', "%{$this->search}%")
                    ->orWhereHas('employee', function ($q) {
                        $q->where('first_name', 'like', "%{$this->search}%")
                          ->orWhere('last_name', 'like', "%{$this->search}%");
                    });
            });
        }

        // 👤 Filtro por empleado
        if ($this->employeeId !== '') {
            $query->where('employee_id', $this->employeeId);
        }

        // 🎯 Filtro por estado exacto
        if ($this->status !== '') {
            $query->where('status', $this->status);
        }

        // 📅 Filtro por fechas
        if ($this->date_from !== '') {
            $query->whereDate('issued_at', '>=', $this->date_from);
        }

        if ($this->date_to !== '') {
            $query->whereDate('issued_at', '<=', $this->date_to);
        }

        // Ejecutar consulta
        $memorandums = $query
            ->orderBy('issued_at', 'desc')
            ->paginate($this->perPage);

        // Métricas (totales por estado)
        $stats = [
            'total' => Memorandum::forCompany($companyId)
                ->whereIn('status', ['acknowledged', 'archived'])
                ->count(),

            'acknowledged' => Memorandum::forCompany($companyId)
                ->where('status', 'acknowledged')
                ->count(),

            'archived' => Memorandum::forCompany($companyId)
                ->where('status', 'archived')
                ->count(),
        ];

        $employees = Employee::where('company_id', $companyId)
            ->orderBy('first_name')
            ->get();

        return view('livewire.memorandums.finalized', [
            'memorandums' => $memorandums,
            'stats' => $stats,
            'employees' => $employees,
        ])->layout('layouts.company');
    }
}

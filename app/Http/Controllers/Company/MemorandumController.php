<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMemorandumRequest;
use App\Http\Requests\UpdateMemorandumRequest;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Memorandum;
use App\Models\MemorandumLog;
use App\Models\User;
use App\Services\ResponsibleUserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MemorandumController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin Empresa']);
    }

    public function index(Request $request, ResponsibleUserService $responsables): View
    {
        $search = $request->string('search')->toString();
        $estado = $request->string('estado')->toString();
        $companyId = $request->user()->company_id;

        $memorandums = Memorandum::query()
            ->with(['author', 'assignedTo', 'approvedBy'])
            ->where('company_id', $companyId)
            ->when($estado, fn ($q) => $q->where('estado', $estado))
            ->search($search)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $indicators = Memorandum::with(['assignedTo'])
            ->where('company_id', $companyId)
            ->get()
            ->groupBy('assigned_to')
            ->map(function ($items) {
                $usuario = $items->first()->assignedTo;
                return (object) [
                    'usuario'     => $usuario,
                    'pendientes'  => $items->where('estado', 'pending')->count(),
                    'en_proceso'  => $items->where('estado', 'en_proceso')->count(),
                    'finalizados' => $items->where('estado', 'finalizado')->count(),
                    'total'       => $items->count(),
                ];
            })
            ->values();

        $stats = [
            'pendiente' => Memorandum::where('company_id', $companyId)->where('estado', 'pending')->count(),
            'en_proceso' => Memorandum::where('company_id', $companyId)->where('estado', 'en_proceso')->count(),
            'finalizado' => Memorandum::where('company_id', $companyId)->where('estado', 'finalizado')->count(),
        ];

        $latestTickets = Memorandum::with(['creador', 'company'])
            ->where('company_id', $companyId)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('company.memorandums.index', [
            'memorandums' => $memorandums,
            'search' => $search,
            'estado' => $estado,
            'estadoOptions' => Memorandum::ESTADOS,
            'responsables' => $responsables->getResponsables(),
            'stats' => $stats,
            'total' => array_sum($stats),
            'latestTickets' => $latestTickets,
            'indicators' => $indicators,
        ]);
    }

    public function create(Request $request, ResponsibleUserService $responsables): View
    {
        $companyId = $request->user()->company_id;

        $users = User::where('company_id', $companyId)
            ->whereDoesntHave('roles', function ($q) {
                $q->where('roles.id', 1); // excluir Super Admin
            })
            ->orderBy('name')
            ->get();

        $employees = Employee::where('company_id', $companyId)
            ->select('id', 'client_id', 'first_name', 'last_name', 'position', 'document_number')
            ->orderBy('first_name')
            ->get();

        return view('company.memorandums.create', [
            'estadoOptions' => Memorandum::ESTADOS,
            'prioridadOptions' => ['urgente', 'alta', 'media', 'baja'],
            'responsables' => $responsables->getResponsables(),
            'clientes' => Client::orderBy('business_name')->get(),
            'users' => $users,
            'employees' => $employees,
        ]);
    }

    public function store(StoreMemorandumRequest $request): RedirectResponse
    {
        $companyId = $request->user()->company_id;
        $data = $request->validated();

        $memorandum = Memorandum::create([
            'company_id' => $companyId,
            'author_id' => $request->user()->id,
            'puesto' => $data['puesto'],
            'employee_name' => $data['name'] ?? null,
            'employee_document' => $data['cedula'] ?? null,
            'employee_position' => $data['cargo'] ?? null,
            'assigned_to' => $data['assigned_to'] ?? null,
            'approved_by' => $data['approved_by'] ?? null,
            'title' => $data['title'],
            'body' => $data['body'] ?? null,
            'estado' => $data['estado'] ?? 'pending',
            'prioridad' => $data['prioridad'] ?? 'media',
            'vence_en' => $data['vence_en'] ?? null,
        ]);

        MemorandumLog::create([
            'memorandum_id' => $memorandum->id,
            'user_id' => $request->user()->id,
            'estado_anterior' => $memorandum->estado,
            'estado_nuevo' => $memorandum->estado,
            'comentario' => null,
        ]);

        return redirect()
            ->route('company.memorandums.index')
            ->with('status', 'Memorando creado correctamente.');
    }

    public function show(Request $request, Memorandum $memorandum, ResponsibleUserService $responsables): View
    {
        $this->authorizeCompany($request, $memorandum);

        return view('company.memorandums.show', [
            'memorandum' => $memorandum->load(['author', 'assignedTo', 'approvedBy', 'logs.user']),
            'responsables' => $responsables->getResponsables(),
            'estadoOptions' => Memorandum::ESTADOS,
            'prioridadOptions' => ['urgente', 'alta', 'media', 'baja'],
        ]);
    }

    public function edit(Request $request, Memorandum $memorandum, ResponsibleUserService $responsables): View
    {
        $this->authorizeCompany($request, $memorandum);

        return view('company.memorandums.board', [
            'memorandum' => $memorandum,
            'estadoOptions' => Memorandum::ESTADOS,
            'prioridadOptions' => ['urgente', 'alta', 'media', 'baja'],
            'responsables' => $responsables->getResponsables(),
            'clientes' => Client::orderBy('business_name')->get(),
        ]);
    }

    public function update(UpdateMemorandumRequest $request, Memorandum $memorandum): RedirectResponse
    {
        $this->authorizeCompany($request, $memorandum);

        $data = $request->validated();
        $estadoAnterior = $memorandum->estado;

        $memorandum->update([
            'puesto' => $data['puesto'],
            'assigned_to' => $data['assigned_to'] ?? null,
            'approved_by' => $data['approved_by'] ?? null,
            'title' => $data['title'],
            'body' => $data['body'] ?? null,
            'estado' => $data['estado'],
            'prioridad' => $data['prioridad'],
            'vence_en' => $data['vence_en'] ?? null,
        ]);

        if ($estadoAnterior !== $memorandum->estado) {
            MemorandumLog::create([
                'memorandum_id' => $memorandum->id,
                'user_id' => $request->user()->id,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $memorandum->estado,
                'comentario' => null,
            ]);
        }

        return redirect()
            ->route('company.memorandums.index')
            ->with('status', 'Memorando actualizado correctamente.');
    }

    public function destroy(Request $request, Memorandum $memorandum): RedirectResponse
    {
        $this->authorizeCompany($request, $memorandum);
        $memorandum->delete();

        return redirect()
            ->route('company.memorandums.index')
            ->with('status', 'Memorando eliminado.');
    }

    private function authorizeCompany(Request $request, Memorandum $memorandum): void
    {
        abort_unless($memorandum->company_id === $request->user()->company_id, 403);
    }
}

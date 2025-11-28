<?php

namespace App\Http\Controllers\Company;

use App\Enums\MemorandumStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMemorandumRequest;
use App\Http\Requests\UpdateMemorandumRequest;
use App\Http\Requests\UpdateMemorandumStatusRequest;
use App\Models\Employee;
use App\Models\Memorandum;
use App\Models\MemorandumStatusHistory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemorandumController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin Empresa']);
    }

    public function index(Request $request): View
    {
        $companyId = $request->user()->company_id;

        $memorandums = Memorandum::query()
            ->with(['employee'])
            ->forCompany($companyId)
            ->latest('issued_at')
            ->paginate(15);

        return view('company.memorandums.index', [
            'memorandums' => $memorandums,
            'statusOptions' => MemorandumStatus::options(),
        ]);
    }

    public function create(Request $request): View
    {
        $employees = Employee::query()
            ->where('company_id', $request->user()->company_id)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('company.memorandums.create', [
            'employees' => $employees,
            'statusOptions' => MemorandumStatus::options(),
        ]);
    }

    public function store(StoreMemorandumRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $memorandum = DB::transaction(function () use ($data, $user) {
            $memorandum = Memorandum::create([
                'company_id' => $user->company_id,
                'user_id' => $user->id,
                'employee_id' => $data['employee_id'] ?? null,
                'subject' => $data['subject'],
                'body' => $data['body'],
                'status' => $data['status'] ?? MemorandumStatus::DRAFT,
                'issued_at' => $data['issued_at'] ?? now(),
                'acknowledged_at' => $data['acknowledged_at'] ?? null,
            ]);

            MemorandumStatusHistory::create([
                'memorandum_id' => $memorandum->id,
                'from_status' => null,
                'to_status' => $memorandum->status->value,
                'changed_by' => $user->id,
                'notes' => $data['notes'] ?? null,
            ]);

            return $memorandum;
        });

        return redirect()
            ->route('company.memorandums.show', $memorandum)
            ->with('status', 'Memorándum creado correctamente.');
    }

    public function show(Request $request, Memorandum $memorandum): View
    {
        $this->authorize('view', $memorandum);

        $memorandum->load(['employee', 'statusHistories.changer']);

        return view('company.memorandums.show', [
            'memorandum' => $memorandum,
            'statusOptions' => MemorandumStatus::options(),
        ]);
    }

    public function edit(Request $request, Memorandum $memorandum): View
    {
        $this->authorize('update', $memorandum);

        $employees = Employee::query()
            ->where('company_id', $request->user()->company_id)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('company.memorandums.edit', [
            'memorandum' => $memorandum,
            'employees' => $employees,
            'statusOptions' => MemorandumStatus::options(),
        ]);
    }

    public function update(UpdateMemorandumRequest $request, Memorandum $memorandum): RedirectResponse
    {
        $this->authorize('update', $memorandum);

        $data = $request->validated();
        $previousStatus = $memorandum->status;

        DB::transaction(function () use ($memorandum, $data, $previousStatus, $request) {
            $memorandum->fill([
                'subject' => $data['subject'] ?? $memorandum->subject,
                'body' => $data['body'] ?? $memorandum->body,
                'employee_id' => $data['employee_id'] ?? $memorandum->employee_id,
                'issued_at' => $data['issued_at'] ?? $memorandum->issued_at,
                'acknowledged_at' => $data['acknowledged_at'] ?? $memorandum->acknowledged_at,
            ]);

            if (array_key_exists('status', $data)) {
                $memorandum->status = MemorandumStatus::from($data['status']);
            }

            if ($memorandum->status === MemorandumStatus::ACKNOWLEDGED && !$memorandum->acknowledged_at) {
                $memorandum->acknowledged_at = now();
            }

            $memorandum->save();

            if ($memorandum->status !== $previousStatus) {
                MemorandumStatusHistory::create([
                    'memorandum_id' => $memorandum->id,
                    'from_status' => $previousStatus->value,
                    'to_status' => $memorandum->status->value,
                    'changed_by' => $request->user()->id,
                    'notes' => $data['notes'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('company.memorandums.show', $memorandum)
            ->with('status', 'Memorándum actualizado correctamente.');
    }

    public function changeStatus(UpdateMemorandumStatusRequest $request, Memorandum $memorandum, string $status): RedirectResponse
    {
        $this->authorize('update', $memorandum);

        $newStatus = MemorandumStatus::from($status);

        if ($memorandum->status === $newStatus) {
            return back()->with('status', 'El memorándum ya se encuentra en ese estado.');
        }

        DB::transaction(function () use ($memorandum, $newStatus, $request) {
            $previousStatus = $memorandum->status;

            $memorandum->status = $newStatus;

            if ($newStatus === MemorandumStatus::ACKNOWLEDGED && !$memorandum->acknowledged_at) {
                $memorandum->acknowledged_at = now();
            }

            $memorandum->save();

            MemorandumStatusHistory::create([
                'memorandum_id' => $memorandum->id,
                'from_status' => $previousStatus->value,
                'to_status' => $newStatus->value,
                'changed_by' => $request->user()->id,
                'notes' => $request->validated('notes'),
            ]);
        });

        return redirect()
            ->route('company.memorandums.show', $memorandum)
            ->with('status', 'Estado actualizado correctamente.');
    }

    public function destroy(Request $request, Memorandum $memorandum): RedirectResponse
    {
        $this->authorize('update', $memorandum);

        $memorandum->delete();

        return redirect()
            ->route('company.memorandums.index')
            ->with('status', 'Memorándum eliminado.');
    }
}

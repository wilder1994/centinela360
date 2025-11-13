<?php

namespace App\Http\Controllers\Company;

use App\Enums\MemorandumStatus;
use App\Events\MemorandumCreated;
use App\Events\MemorandumUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMemorandumRequest;
use App\Http\Requests\UpdateMemorandumRequest;
use App\Http\Requests\UpdateMemorandumStatusRequest;
use App\Models\Employee;
use App\Models\Memorandum;
use App\Models\MemorandumStatusHistory;
use App\Notifications\MemorandumNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class MemorandumController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin Empresa']);
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        $company = $user->company;

        $search = $request->string('search')->toString();
        $status = $request->string('status')->toString();

        $query = Memorandum::query()
            ->with(['author', 'employee'])
            ->forCompany($company->id)
            ->search($search);

        if ($status) {
            $query->where('status', $status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('issued_at', '>=', $request->date('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('issued_at', '<=', $request->date('date_to'));
        }

        $memorandums = $query
            ->latest('issued_at')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $statusCounts = Memorandum::forCompany($company->id)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $recentUpdates = MemorandumStatusHistory::with(['memorandum', 'changer'])
            ->whereHas('memorandum', fn ($builder) => $builder->forCompany($company->id))
            ->latest()
            ->limit(5)
            ->get();

        return view('company.memorandums.index', [
            'company' => $company,
            'memorandums' => $memorandums,
            'statusOptions' => MemorandumStatus::options(),
            'statusCounts' => $statusCounts,
            'totalMemorandums' => Memorandum::forCompany($company->id)->count(),
            'recentUpdates' => $recentUpdates,
            'filters' => [
                'search' => $search,
                'status' => $status,
                'date_from' => $request->input('date_from'),
                'date_to' => $request->input('date_to'),
            ],
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
        $company = $user->company;
        $data = $request->validated();

        $memorandum = DB::transaction(function () use ($data, $user, $company, $request) {
            $status = $data['status'] ?? MemorandumStatus::DRAFT->value;

            $memorandum = Memorandum::create([
                'company_id' => $company->id,
                'user_id' => $user->id,
                'employee_id' => $data['employee_id'] ?? null,
                'subject' => $data['subject'],
                'body' => $data['body'],
                'status' => $status,
                'issued_at' => $data['issued_at'] ?? now(),
                'acknowledged_at' => $data['acknowledged_at'] ?? null,
            ]);

            MemorandumStatusHistory::create([
                'memorandum_id' => $memorandum->id,
                'from_status' => null,
                'to_status' => $memorandum->status->value,
                'changed_by' => $user->id,
                'notes' => $request->input('notes'),
            ]);

            return $memorandum;
        });

        $memorandum->load(['author', 'employee', 'statusHistories.changer']);

        event(new MemorandumCreated($memorandum));

        Notification::send($user, new MemorandumNotification($memorandum, 'created'));

        return redirect()
            ->route('company.memorandums.show', $memorandum)
            ->with('status', 'Memorándum registrado correctamente.');
    }

    public function show(Request $request, Memorandum $memorandum): View
    {
        $this->authorize('view', $memorandum);

        $memorandum->load(['author', 'employee', 'statusHistories.changer']);

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

        $memorandum->load(['author', 'employee']);

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
                'employee_id' => array_key_exists('employee_id', $data)
                    ? $data['employee_id']
                    : $memorandum->employee_id,
                'issued_at' => $data['issued_at'] ?? $memorandum->issued_at,
                'acknowledged_at' => $data['acknowledged_at'] ?? $memorandum->acknowledged_at,
            ]);

            if (array_key_exists('status', $data) && $data['status'] !== $previousStatus->value) {
                $memorandum->status = $data['status'];
            }

            if ($memorandum->status === MemorandumStatus::ACKNOWLEDGED && !$memorandum->acknowledged_at) {
                $memorandum->acknowledged_at = now();
            }

            $memorandum->save();

            if (array_key_exists('status', $data) && $data['status'] !== $previousStatus->value) {
                MemorandumStatusHistory::create([
                    'memorandum_id' => $memorandum->id,
                    'from_status' => $previousStatus->value,
                    'to_status' => $memorandum->status->value,
                    'changed_by' => $request->user()->id,
                    'notes' => $request->input('notes'),
                ]);
            }
        });

        $memorandum->refresh()->load(['author', 'employee', 'statusHistories.changer']);

        event(new MemorandumUpdated($memorandum));

        Notification::send($request->user(), new MemorandumNotification($memorandum, 'updated'));

        return redirect()
            ->route('company.memorandums.show', $memorandum)
            ->with('status', 'Memorándum actualizado correctamente.');
    }

    public function updateStatus(UpdateMemorandumStatusRequest $request, Memorandum $memorandum): RedirectResponse
    {
        $this->authorize('update', $memorandum);

        $newStatus = MemorandumStatus::from($request->validated('status'));
        $previousStatus = $memorandum->status;

        if ($previousStatus === $newStatus) {
            return back()->with('status', 'El memorándum ya se encuentra en el estado seleccionado.');
        }

        DB::transaction(function () use ($memorandum, $newStatus, $previousStatus, $request) {
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

        $memorandum->refresh()->load(['author', 'employee', 'statusHistories.changer']);

        event(new MemorandumUpdated($memorandum));

        Notification::send($request->user(), new MemorandumNotification($memorandum, 'updated'));

        return redirect()
            ->route('company.memorandums.show', $memorandum)
            ->with('status', 'El estado del memorándum se actualizó correctamente.');
    }
}

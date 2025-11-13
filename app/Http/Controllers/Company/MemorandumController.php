<?php

namespace App\Http\Controllers\Company;

use App\Events\MemorandumCreated;
use App\Events\MemorandumUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMemorandumRequest;
use App\Http\Requests\UpdateMemorandumRequest;
use App\Http\Requests\UpdateMemorandumStatusRequest;
use App\Models\Memorandum;
use App\Models\MemorandumStatusHistory;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MemorandumController extends Controller
{
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = request()->user();

        /** @var LengthAwarePaginator $memorandums */
        $memorandums = Memorandum::query()
            ->where('company_id', $user->company_id)
            ->with(['responsible'])
            ->orderByDesc('created_at')
            ->paginate(10);

        $responsibles = User::query()
            ->where('company_id', $user->company_id)
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('company.memorandos.index', compact('memorandums', 'responsibles'));
    }

    public function show(Memorandum $memorandum): View
    {
        $this->authorizeForUser(request()->user(), 'view', $memorandum);

        $memorandum->load(['responsible', 'creator', 'statusHistories.changer']);

        return view('company.memorandos.show', compact('memorandum'));
    }

    public function store(StoreMemorandumRequest $request): RedirectResponse
    {
        $user = $request->user();

        $memorandum = DB::transaction(function () use ($request, $user) {
            $data = $request->validated();
            $status = $data['status'] ?? Memorandum::STATUS_DRAFT;

            $memorandum = Memorandum::create([
                'company_id' => $user->company_id,
                'title' => $data['title'],
                'body' => $data['body'],
                'status' => $status,
                'responsible_id' => $data['responsible_id'],
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            MemorandumStatusHistory::create([
                'memorandum_id' => $memorandum->id,
                'from_status' => null,
                'to_status' => $status,
                'changed_by' => $user->id,
                'notes' => __('Creación del memorando'),
            ]);

            return $memorandum;
        });

        MemorandumCreated::dispatch($memorandum);

        return redirect()
            ->route('company.memorandos.show', $memorandum)
            ->with('status', __('Memorando creado correctamente.'));
    }

    public function update(UpdateMemorandumRequest $request, Memorandum $memorandum): RedirectResponse
    {
        $this->authorizeForUser($request->user(), 'update', $memorandum);

        $user = $request->user();

        $previousStatus = $memorandum->status;

        $data = $request->validated();

        $memorandum->fill($data);
        $memorandum->updated_by = $user->id;
        $memorandum->save();

        if (array_key_exists('status', $data) && $data['status'] !== $previousStatus) {
            MemorandumStatusHistory::create([
                'memorandum_id' => $memorandum->id,
                'from_status' => $previousStatus,
                'to_status' => $data['status'],
                'changed_by' => $user->id,
                'notes' => __('Actualización manual del memorando'),
            ]);
        }

        MemorandumUpdated::dispatch($memorandum);

        return redirect()
            ->route('company.memorandos.show', $memorandum)
            ->with('status', __('Memorando actualizado correctamente.'));
    }

    public function updateStatus(UpdateMemorandumStatusRequest $request, Memorandum $memorandum): RedirectResponse
    {
        $this->authorizeForUser($request->user(), 'update', $memorandum);

        $user = $request->user();
        $previousStatus = $memorandum->status;
        $data = $request->validated();

        if ($previousStatus === $data['status']) {
            return redirect()
                ->route('company.memorandos.show', $memorandum)
                ->with('status', __('El memorando ya se encuentra en el estado seleccionado.'));
        }

        $memorandum->status = $data['status'];
        $memorandum->updated_by = $user->id;
        $memorandum->save();

        MemorandumStatusHistory::create([
            'memorandum_id' => $memorandum->id,
            'from_status' => $previousStatus,
            'to_status' => $data['status'],
            'changed_by' => $user->id,
            'notes' => $data['notes'] ?? null,
        ]);

        MemorandumUpdated::dispatch($memorandum);

        return redirect()
            ->route('company.memorandos.show', $memorandum)
            ->with('status', __('Estado del memorando actualizado correctamente.'));
    }
}

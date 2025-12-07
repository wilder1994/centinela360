<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Models\ClientNote;
use App\Models\ClientService;
use App\Models\ServiceType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin Empresa']);
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $companyId = $request->user()->company_id;

        $clients = Client::query()
            ->with(['services', 'notes.user'])
            ->forCompany($companyId)
            ->withoutArchived()
            ->search($search)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('company.clients.index', [
            'clients' => $clients,
            'search' => $search,
            'serviceTypes' => ServiceType::query()->where('company_id', $companyId)->orderBy('name')->get(),
        ]);
    }

    public function archived(Request $request): View
    {
        $search = $request->string('search')->toString();
        $companyId = $request->user()->company_id;

        $clients = Client::query()
            ->with(['services', 'notes.user'])
            ->forCompany($companyId)
            ->onlyArchived()
            ->search($search)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('company.clients.archived', [
            'clients' => $clients,
            'search' => $search,
        ]);
    }

    public function create(Request $request): View
    {
        return view('company.clients.create', $this->formOptions($request->user()->company_id));
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        $companyId = $request->user()->company_id;
        $data = $request->validated();

        DB::transaction(function () use ($data, $companyId) {
            $client = Client::create([
                'company_id' => $companyId,
                'business_name' => $data['business_name'],
                'nit' => $data['nit'],
                'address' => $data['address'],
                'neighborhood' => $data['neighborhood'],
                'city' => $data['city'],
                'service_count' => count($data['service_types']),
                'email' => $data['email'],
                'representative_name' => $data['representative_name'],
                'quadrant' => $data['quadrant'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'] ?? null,
            ]);

            $this->syncServices($client, $data['service_types'], $data['service_schedules']);
            $client->notes()->create([
                'user_id' => auth()->id(),
                'body' => 'Cliente creado.',
            ]);
        });

        return redirect()
            ->route('company.clients.index')
            ->with('status', 'Cliente creado correctamente.');
    }

    public function edit(Request $request, Client $client): View
    {
        $this->authorizeClient($request, $client);

        $client->load('services');

        return view('company.clients.edit', array_merge([
            'client' => $client,
        ], $this->formOptions($request->user()->company_id)));
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $this->authorizeClient($request, $client);
        $data = $request->validated();

        DB::transaction(function () use ($client, $data) {
            $client->update([
                'business_name' => $data['business_name'],
                'nit' => $data['nit'],
                'address' => $data['address'],
                'neighborhood' => $data['neighborhood'],
                'city' => $data['city'],
                'service_count' => count($data['service_types']),
                'email' => $data['email'],
                'representative_name' => $data['representative_name'],
                'quadrant' => $data['quadrant'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'] ?? null,
            ]);

            $this->syncServices($client, $data['service_types'], $data['service_schedules']);
            $client->notes()->create([
                'user_id' => auth()->id(),
                'body' => 'Cliente actualizado.',
            ]);
        });

        return redirect()
            ->route('company.clients.index')
            ->with('status', 'Cliente actualizado correctamente.');
    }

    public function destroy(Request $request, Client $client): RedirectResponse
    {
        $this->authorizeClient($request, $client);

        $data = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        $client->update(['archived_at' => Carbon::now()]);
        $client->notes()->create([
            'user_id' => $request->user()->id,
            'body' => 'Archivado: ' . $data['comment'],
        ]);

        return redirect()
            ->route('company.clients.index')
            ->with('status', 'Cliente archivado correctamente.');
    }

    public function unarchive(Request $request, Client $client): RedirectResponse
    {
        $this->authorizeClient($request, $client);

        $data = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        $client->update(['archived_at' => null]);
        $client->notes()->create([
            'user_id' => $request->user()->id,
            'body' => 'Desarchivado: ' . $data['comment'],
        ]);

        return back()->with('status', 'Cliente desarchivado correctamente.');
    }

    public function storeNote(Request $request, Client $client): RedirectResponse
    {
        $this->authorizeClient($request, $client);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $client->notes()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        return back()->with('status', 'Nota registrada.');
    }

    private function authorizeClient(Request $request, Client $client): void
    {
        abort_unless($client->company_id === $request->user()->company_id, 403);
    }

    private function syncServices(Client $client, array $types, array $schedules): void
    {
        $client->services()->delete();

        $services = collect($types)
            ->zip($schedules)
            ->map(fn ($pair) => new ClientService([
                'service_type' => $pair[0],
                'service_schedule' => $pair[1],
            ]));

        $client->services()->saveMany($services);
    }

    private function formOptions(int $companyId): array
    {
        $serviceTypesOptions = ServiceType::query()
            ->where('company_id', $companyId)
            ->orderBy('name')
            ->pluck('name')
            ->toArray();

        return [
            'serviceTypesOptions' => $serviceTypesOptions,
            'serviceScheduleOptions' => ['12H', '8H', 'Ocasional'],
        ];
    }
}

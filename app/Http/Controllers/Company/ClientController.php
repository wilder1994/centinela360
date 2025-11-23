<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Models\ClientService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            ->with('services')
            ->forCompany($companyId)
            ->search($search)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('company.clients.index', [
            'clients' => $clients,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('company.clients.create', $this->formOptions());
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
            ]);

            $this->syncServices($client, $data['service_types'], $data['service_schedules']);
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
        ], $this->formOptions()));
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
            ]);

            $this->syncServices($client, $data['service_types'], $data['service_schedules']);
        });

        return redirect()
            ->route('company.clients.index')
            ->with('status', 'Cliente actualizado correctamente.');
    }

    public function destroy(Request $request, Client $client): RedirectResponse
    {
        $this->authorizeClient($request, $client);
        $client->delete();

        return redirect()
            ->route('company.clients.index')
            ->with('status', 'Cliente eliminado correctamente.');
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

    private function formOptions(): array
    {
        return [
            'serviceTypesOptions' => ['Ronda', 'Portería', 'Ocasional'],
            'serviceScheduleOptions' => ['12H', '8H', 'Ocasional'],
        ];
    }
}

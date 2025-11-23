@extends('layouts.company')

@section('content')
<div class="animate-fadeIn space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Clientes</h1>
            <p class="text-sm text-gray-500">Listado de clientes y servicios</p>
        </div>

        <a href="{{ route('company.clients.create') }}" class="bg-[var(--primary)] text-white px-4 py-2 rounded-lg shadow">
            + Nuevo cliente
        </a>
    </div>

    <div class="bg-white rounded-xl shadow p-4 flex flex-col gap-3">
        <form method="GET" class="flex flex-col md:flex-row gap-3 items-start md:items-center">
            <div class="flex-1 w-full">
                <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre, NIT o ciudad..."
                       class="w-full input rounded-lg border border-gray-200 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
            </div>
            <button type="submit" class="bg-[var(--primary)] text-white px-4 py-2 rounded-lg">Buscar</button>
        </form>
        @if (session('status'))
            <div class="text-sm text-green-700 bg-green-50 border border-green-200 px-3 py-2 rounded-lg">{{ session('status') }}</div>
        @endif
    </div>

    <!-- Tabla (escritorio) -->
    <div class="hidden md:block bg-white rounded-xl shadow overflow-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Cliente</th>
                    <th class="px-4 py-3">NIT</th>
                    <th class="px-4 py-3">Servicios</th>
                    <th class="px-4 py-3">Tipo / Horario</th>
                    <th class="px-4 py-3">Cuadrante</th>
                    <th class="px-4 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse ($clients as $client)
                    <tr class="border-t">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('images/default-logo.png') }}" class="w-10 h-10 object-contain" alt="logo">
                                <div>
                                    <div class="font-semibold">{{ $client->business_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $client->neighborhood }} • {{ $client->city }}</div>
                                    <div class="text-xs text-gray-500">{{ $client->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $client->nit }}</td>
                        <td class="px-4 py-3">{{ $client->service_count }}</td>
                        <td class="px-4 py-3">{{ $client->service_summary ?: '—' }}</td>
                        <td class="px-4 py-3">{{ $client->quadrant }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('company.clients.edit', $client) }}" class="text-[var(--primary)] hover:underline">Editar</a>
                                <form method="POST" action="{{ route('company.clients.destroy', $client) }}" onsubmit="return confirm('¿Eliminar cliente?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">No hay clientes registrados aún.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Cards (móvil) -->
    <div class="grid md:hidden gap-4">
        @forelse ($clients as $client)
            <div class="bg-white p-4 rounded-xl shadow space-y-2">
                <div class="flex items-start gap-3">
                    <img src="{{ asset('images/default-logo.png') }}" class="w-12 h-12 object-contain" alt="logo">
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <div>
                                <p class="font-semibold">{{ $client->business_name }}</p>
                                <p class="text-sm text-gray-500">NIT {{ $client->nit }}</p>
                                <p class="text-xs text-gray-500">{{ $client->neighborhood }} • {{ $client->city }}</p>
                            </div>
                            <div class="text-sm text-gray-500">{{ $client->service_count }} servicios</div>
                        </div>
                        <p class="text-xs text-gray-500">{{ $client->service_summary ?: 'Sin servicios registrados' }}</p>
                        <p class="text-xs text-gray-500">Cuadrante: {{ $client->quadrant }}</p>
                        <p class="text-xs text-gray-500">{{ $client->email }}</p>

                        <div class="mt-3 flex items-center gap-4">
                            <a href="{{ route('company.clients.edit', $client) }}" class="text-[var(--primary)] text-sm hover:underline">Editar</a>
                            <form method="POST" action="{{ route('company.clients.destroy', $client) }}" onsubmit="return confirm('¿Eliminar cliente?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 text-sm hover:underline">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500">No hay clientes registrados aún.</div>
        @endforelse
    </div>

    <div class="hidden md:flex justify-end">
        {{ $clients->links() }}
    </div>
</div>
@endsection

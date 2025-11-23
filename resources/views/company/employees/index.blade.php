@extends('layouts.company')

@section('content')
<div class="animate-fadeIn space-y-6 max-w-7xl mx-auto">
    <div class="flex items-center justify-between px-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Empleados</h1>
            <p class="text-sm text-gray-500">Gestión de la base de datos de empleados</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('company.employees.create') }}" class="bg-[var(--primary)] text-white px-4 py-2 rounded-lg shadow">
                + Nuevo empleado
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow px-4 py-3">
        <form method="GET" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-3 w-full md:w-auto">
                <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre, documento o teléfono" class="input w-full md:w-80 rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
                <button type="submit" class="px-4 py-2 bg-[var(--primary)] text-white rounded-lg">Buscar</button>
            </div>
            @if(session('status'))
                <div class="text-sm text-green-700 bg-green-50 px-3 py-2 rounded-lg">{{ session('status') }}</div>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Empleado</th>
                    <th class="px-4 py-3 text-left">Documento</th>
                    <th class="px-4 py-3 text-left">Teléfono</th>
                    <th class="px-4 py-3 text-left">Cliente</th>
                    <th class="px-4 py-3 text-left">Servicio</th>
                    <th class="px-4 py-3 text-left">Carnet vence</th>
                    <th class="px-4 py-3 text-left">Estado</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 divide-y divide-gray-100">
                @forelse($employees as $employee)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-semibold">{{ $employee->fullName }}</div>
                            <div class="text-xs text-gray-500">{{ $employee->position }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $employee->document_type }} {{ $employee->document_number }}</td>
                        <td class="px-4 py-3">{{ $employee->phone }}</td>
                        <td class="px-4 py-3">{{ $employee->client?->business_name ?? 'Sin asignar' }}</td>
                        <td class="px-4 py-3">{{ $employee->service_type ?? '—' }}</td>
                        <td class="px-4 py-3">{{ optional($employee->badge_expires_at)->format('Y-m-d') ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @php
                                $statusColors = [
                                    'Activo' => 'bg-green-100 text-green-700',
                                    'En vacaciones' => 'bg-yellow-100 text-yellow-700',
                                    'Incapacitado' => 'bg-orange-100 text-orange-700',
                                    'Desprogramado' => 'bg-gray-100 text-gray-700',
                                    'Calamidad' => 'bg-red-100 text-red-700',
                                    'Despedido' => 'bg-red-100 text-red-700',
                                ];
                                $badgeClass = $statusColors[$employee->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs {{ $badgeClass }}">{{ $employee->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-center space-x-2">
                            <a href="{{ route('company.employees.edit', $employee) }}" class="text-[var(--primary)] hover:underline">Editar</a>
                            <form action="{{ route('company.employees.destroy', $employee) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este empleado?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">No hay empleados registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-4 py-3">
            {{ $employees->links() }}
        </div>
    </div>
</div>
@endsection

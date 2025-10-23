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
                <tr class="border-t">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/default-logo.png') }}" class="w-10 h-10 object-contain" alt="logo">
                            <div>
                                <div class="font-semibold">Conjunto Ejemplo</div>
                                <div class="text-xs text-gray-500">Barrio Centro • Bogotá</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">800.123.456-7</td>
                    <td class="px-4 py-3">2</td>
                    <td class="px-4 py-3">Ronda • 12H</td>
                    <td class="px-4 py-3">A1</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('company.clients.edit', 1) }}" class="text-[var(--primary)] hover:underline">Editar</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Cards (móvil) -->
    <div class="grid md:hidden gap-4">
        <div class="bg-white p-4 rounded-xl shadow">
            <div class="flex items-start gap-3">
                <img src="{{ asset('images/default-logo.png') }}" class="w-12 h-12 object-contain" alt="logo">
                <div class="flex-1">
                    <div class="flex justify-between">
                        <div>
                            <p class="font-semibold">Conjunto Ejemplo</p>
                            <p class="text-sm text-gray-500">NIT 800.123.456-7</p>
                        </div>
                        <div class="text-sm text-gray-500">2 servicios</div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('company.clients.edit', 1) }}" class="text-[var(--primary)] text-sm hover:underline">Editar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

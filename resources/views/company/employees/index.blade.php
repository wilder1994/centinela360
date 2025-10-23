@extends('layouts.company')

@section('content')
<div class="animate-fadeIn space-y-6">

    <div class="flex items-center justify-between">
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

    <!-- Tabla (escritorio) -->
    <div class="hidden md:block bg-white rounded-xl shadow overflow-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Empleado</th>
                    <th class="px-4 py-3 text-center">Documento</th>
                    <th class="px-4 py-3 text-center">Número de celular</th> 
                    <th class="px-4 py-3 text-center">RH</th>
                    <th class="px-4 py-3 text-center">Carnet vence</th>
                    <th class="px-4 py-3 text-center">Vacaciones</th>
                    <th class="px-4 py-3 text-center">Estado</th> <!-- Nueva columna -->
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                <!-- Ejemplo de fila estática -->
                <tr class="border-t">
                    <td class="px-4 py-3 flex items-center gap-3">
                        <img src="{{ asset('images/default-avatar.png') }}" class="w-10 h-10 rounded-full object-cover" alt="avatar">
                        <div>
                            <div class="font-semibold">Juan Pérez</div>
                            <div class="text-xs text-gray-500">Guarda</div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center">CC 1.234.567.89</td>
                    <td class="px-4 py-3 text-center">300 123 4567</td> 
                    <td class="px-4 py-3 text-center">O+</td>
                    <td class="px-4 py-3 text-center">2026-03-10</td>
                    <td class="px-4 py-3 text-center">10 días</td>
                    <td class="px-4 py-3 text-center">
                        <!-- Estado de ejemplo: Activo -->
                        <span class="px-2 py-1 rounded-full bg-green-100 text-green-600">Activo</span> <!-- Se puede cambiar dependiendo del estado -->
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('company.employees.edit', 1) }}" class="text-[var(--primary)] hover:underline">Editar</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Cards (móvil) -->
    <div class="grid md:hidden gap-4">
        <div class="bg-white p-4 rounded-xl shadow">
            <div class="flex items-start gap-3">
                <img src="{{ asset('images/default-avatar.png') }}" class="w-12 h-12 rounded-full" alt="avatar">
                <div class="flex-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold">Juan Pérez</p>
                            <p class="text-sm text-gray-500">Guarda • CC 1.234.567.89</p>
                        </div>
                        <div class="text-sm text-gray-500">10 días</div>
                    </div>

                    <div class="mt-3 flex items-center gap-2">
                        <a href="{{ route('company.employees.edit', 1) }}" class="text-[var(--primary)] text-sm hover:underline">Editar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

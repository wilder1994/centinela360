@extends('layouts.company')

@section('content')
<div class="animate-fadeIn space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Memorandos</h1>
            <p class="text-sm text-gray-500">Seguimiento y trazabilidad de los memorandos generados en la compañía.</p>
        </div>
        <a href="{{ route('company.memorandos.create') }}" class="bg-[var(--primary)] text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition-colors">
            + Nuevo memorando
        </a>
    </div>

    <div class="bg-white rounded-xl shadow">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 p-4 border-b border-gray-100">
            <form action="#" method="GET" class="flex items-center gap-2">
                <label for="search" class="sr-only">Buscar</label>
                <input id="search" type="text" name="search" placeholder="Buscar memorando" class="w-64 rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                <button type="submit" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">Buscar</button>
            </form>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span class="px-3 py-1 rounded-full bg-blue-50 text-[var(--primary)]">En seguimiento</span>
                <span class="px-3 py-1 rounded-full bg-green-50 text-green-600">Cerrado</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left">Código</th>
                        <th class="px-4 py-3 text-left">Colaborador</th>
                        <th class="px-4 py-3 text-left">Asunto</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3 text-center">Creado</th>
                        <th class="px-4 py-3 text-center">Adjuntos</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <tr class="border-t">
                        <td class="px-4 py-3 font-medium text-gray-800">MEM-2024-023</td>
                        <td class="px-4 py-3">Juan Pérez</td>
                        <td class="px-4 py-3">Incumplimiento de protocolos de seguridad</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-600 text-xs font-semibold">En revisión</span>
                        </td>
                        <td class="px-4 py-3 text-center">12 Mar 2024</td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ Storage::url('memorandos/MEM-2024-023.pdf') }}" class="text-[var(--primary)] hover:underline">PDF</a>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('company.memorandos.show', 23) }}" class="text-[var(--primary)] hover:underline">Ver</a>
                            <span class="text-gray-300 mx-1">|</span>
                            <a href="{{ route('company.memorandos.edit', 23) }}" class="text-[var(--primary)] hover:underline">Editar</a>
                        </td>
                    </tr>
                    <tr class="border-t">
                        <td class="px-4 py-3 font-medium text-gray-800">MEM-2024-018</td>
                        <td class="px-4 py-3">Laura Rodríguez</td>
                        <td class="px-4 py-3">Reconocimiento por desempeño destacado</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 rounded-full bg-green-100 text-green-600 text-xs font-semibold">Cerrado</span>
                        </td>
                        <td class="px-4 py-3 text-center">05 Mar 2024</td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ Storage::url('memorandos/MEM-2024-018.pdf') }}" class="text-[var(--primary)] hover:underline">PDF</a>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('company.memorandos.show', 18) }}" class="text-[var(--primary)] hover:underline">Ver</a>
                            <span class="text-gray-300 mx-1">|</span>
                            <a href="{{ route('company.memorandos.edit', 18) }}" class="text-[var(--primary)] hover:underline">Editar</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

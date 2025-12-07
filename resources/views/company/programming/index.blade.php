@extends('layouts.company')

@section('content')
<div class="text-white space-y-6">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm uppercase tracking-wide text-gray-500">Operaciones</p>
            <h1 class="text-3xl font-semibold text-white">Programaci?n operativa</h1>
            <p class="text-gray-400 text-sm mt-1">Administra mallas, disponibilidad y reportes desde un solo lugar.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('company.programming.create') }}" class="px-4 py-2 border border-gray-700 text-gray-200 rounded-md hover:bg-gray-800 transition">Nueva malla</a>
            <button class="px-4 py-2 bg-primary text-white rounded-md shadow-sm hover:brightness-110 transition">Exportar</button>
        </div>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-lg">
        <div class="flex flex-wrap gap-2 border-b border-gray-800 p-4">
            <button class="px-4 py-2 bg-primary text-white border border-primary rounded-md font-medium">Mallas</button>
            <button class="px-4 py-2 text-gray-300 bg-gray-800 border border-gray-700 rounded-md hover:bg-gray-700">Disponibles</button>
            <button class="px-4 py-2 text-gray-300 bg-gray-800 border border-gray-700 rounded-md hover:bg-gray-700">Cartas de presentaci?n</button>
            <button class="px-4 py-2 text-gray-300 bg-gray-800 border border-gray-700 rounded-md hover:bg-gray-700">Reportes radiales</button>
        </div>

        <div class="p-4 space-y-4">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
                <div class="flex-1 flex items-center gap-3">
                    <input type="text" class="w-full bg-gray-800 border border-gray-700 text-gray-300 rounded-md px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary" placeholder="Buscar por: c?dula, nombre de guarda o nombre del puesto...">
                </div>
                <div class="flex items-center gap-2">
                    <button class="px-4 py-2 bg-primary text-white rounded-md hover:brightness-110 transition">Buscar</button>
                    <button class="px-4 py-2 bg-gray-800 text-gray-200 border border-gray-700 rounded-md hover:bg-gray-700 transition">Limpiar</button>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 text-sm text-gray-200">
                <span class="px-3 py-1 bg-gray-800 border border-gray-700 rounded">1er descanso</span>
                <span class="px-3 py-1 bg-gray-800 border border-gray-700 rounded">2do descanso</span>
                <span class="px-3 py-1 bg-gray-800 border border-gray-700 rounded">Vacaciones</span>
                <span class="px-3 py-1 bg-gray-800 border border-gray-700 rounded">Incapacitados</span>
                <span class="px-3 py-1 bg-gray-800 border border-gray-700 rounded">Relevantes</span>
                <span class="px-3 py-1 bg-gray-800 border border-gray-700 rounded">Guardas por puesto</span>
                <span class="px-3 py-1 bg-gray-800 border border-gray-700 rounded">Historial por puesto</span>
            </div>
        </div>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 text-gray-300 space-y-4">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-white">Malla programada</h2>
                <p class="text-sm text-gray-400">Filtra por c?dula, nombre o puesto para ver asignaciones.</p>
            </div>
            <div class="flex flex-wrap gap-2 text-xs">
                <span class="px-3 py-1 bg-gray-800 border border-gray-700 rounded">Semana actual</span>
                <span class="px-3 py-1 bg-gray-800 border border-gray-700 rounded">Total guardas: 0</span>
            </div>
        </div>
        <div class="border border-dashed border-gray-700 rounded-md p-6 text-center text-gray-500">
            Aqu? se mostrar? la tabla o malla filtrada.
        </div>
    </div>
</div>
@endsection

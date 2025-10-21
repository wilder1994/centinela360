@extends('layouts.company')

@section('content')
<div class="text-white">

    {{-- Título --}}
    <h1 class="text-2xl font-semibold mb-6">Programación operativa</h1>

    {{-- Pestañas superiores --}}
    <div class="flex gap-3 mb-6 border-b border-gray-700 pb-2">
        <button class="px-4 py-2 bg-gray-800 border border-gray-700 rounded-md text-white">Mallas</button>
        <button class="px-4 py-2 hover:bg-gray-800 border border-gray-700 rounded-md text-gray-300">Disponibles</button>
        <button class="px-4 py-2 hover:bg-gray-800 border border-gray-700 rounded-md text-gray-300">Cartas de presentación</button>
        <button class="px-4 py-2 hover:bg-gray-800 border border-gray-700 rounded-md text-gray-300">Reportes radiales</button>
    </div>

    {{-- Barra de búsqueda y filtros --}}
    <div class="bg-gray-900 border border-gray-700 rounded-lg p-4 mb-6">
        <div class="flex items-center gap-3 mb-3">
            <input type="text" class="w-full bg-gray-800 border border-gray-700 text-gray-300 rounded-md px-3 py-2"
                   placeholder="Buscar por: cédula, nombre de guarda o nombre del puesto...">
            <button class="px-4 py-2 bg-primary text-white rounded-md">Buscar</button>
            <button class="px-4 py-2 bg-gray-700 text-gray-300 rounded-md">Limpiar</button>
        </div>

        <div class="flex flex-wrap gap-3 text-sm text-gray-300">
            <span class="px-3 py-1 bg-gray-800 border border-gray-700 rounded">1er descanso</span>
            <span class="px-3 py-1 bg-gray-800 border border-gray-700 rounded">2do descanso</span>
            <span class="px-3 py-1 bg-gray-800 border border-gray-700 rounded">Vacaciones</span>
            <span class="px-3 py-1 bg-gray-800 border border-gray-700 rounded">Incapacitados</span>
            <span class="px-3 py-1 bg-gray-800 border border-gray-700 rounded">Relevantes</span>
            <span class="px-3 py-1 bg-gray-800 border border-gray-700 rounded">Guardas por puesto</span>
            <span class="px-3 py-1 bg-gray-800 border border-gray-700 rounded">Historial por puesto</span>
        </div>
    </div>

    {{-- Contenedor de resultados --}}
    <div class="bg-gray-900 border border-gray-700 rounded-lg p-6 text-gray-400 text-center">
        Aquí se mostrará la tabla o malla filtrada.
    </div>

</div>
@endsection

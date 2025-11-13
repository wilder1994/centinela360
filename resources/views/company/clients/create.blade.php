@extends('layouts.company')

@section('content')
<div class="animate-fadeIn space-y-8 max-w-6xl mx-auto px-6 relative">
    <!-- Fondo con degradado -->
    <div class="absolute inset-0 -z-10 bg-gradient-to-b from-[var(--primary)] to-[var(--secondary)] opacity-80"></div>

    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-semibold text-gray-800">Nuevo cliente</h1>
        <a href="{{ route('company.clients.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Volver</a>
    </div>

    <form class="bg-gradient-to-b from-[var(--primary)] to-[var(--secondary)] p-8 rounded-xl shadow-2xl space-y-8 mt-8 border border-gray-200 max-w-4xl mx-auto relative">
        
        <!-- Logo / Imagen con ícono edificio -->
        <div class="flex justify-center mb-10">
            <label for="client-logo" class="cursor-pointer rounded-full relative w-24 h-24 border-4 border-white shadow-xl bg-white flex items-center justify-center">
                <!-- Ícono SVG limpio tipo edificio lineal -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M4 21V7l8-4 8 4v14M9 21v-6h6v6"/>
                </svg>
                <input type="file" id="client-logo" class="absolute inset-0 opacity-0 cursor-pointer" />
            </label>
        </div>

        <!-- Información del cliente -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            <div>
                <label class="block text-sm font-medium text-gray-700">Razón social</label>
                <input type="text" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Razón social / nombre" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">NIT</label>
                <input type="text" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="NIT" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Dirección</label>
                <input type="text" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Dirección">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Barrio</label>
                <input type="text" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Barrio">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Ciudad</label>
                <input type="text" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Ciudad">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Cantidad de servicios</label>
                <input type="number" min="0" value="1" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tipo de servicio</label>
                <select class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
                    <option>Ronda</option>
                    <option>Portería</option>
                    <option>Ocasional</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Horario</label>
                <select class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
                    <option>12H</option>
                    <option>8H</option>
                    <option>Ocasional</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Número de cuadrante</label>
                <input type="text" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
            </div>
        </div>

        <!-- Botones -->
        <div class="flex items-center justify-end gap-4 mt-6">
            <button type="submit" class="bg-[var(--primary)] text-white px-6 py-3 rounded-lg shadow-md hover:bg-[var(--primary)]/90 transition-all">Guardar</button>
            <a href="{{ route('company.clients.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">Cancelar</a>
        </div>
    </form>
</div>
@endsection

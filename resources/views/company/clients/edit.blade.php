@extends('layouts.company')

@section('content')
<div class="animate-fadeIn space-y-8 max-w-6xl mx-auto px-6 relative">
    <!-- Fondo con degradado -->
    <div class="absolute inset-0 -z-10 bg-gradient-to-b from-[var(--primary)] to-[var(--secondary)] opacity-80"></div>

    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-semibold text-gray-800">Editar cliente</h1>
        <a href="{{ route('company.clients.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Volver</a>
    </div>

    <form class="bg-gradient-to-b from-[var(--primary)] to-[var(--secondary)] p-8 rounded-xl shadow-2xl space-y-8 mt-8 border border-gray-200 max-w-4xl mx-auto relative">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            <div>
                <label class="block text-sm font-medium text-gray-700">Razón social</label>
                <input type="text" value="Conjunto Ejemplo" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">NIT</label>
                <input type="text" value="800123456-7" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Dirección</label>
                <input type="text" value="Calle 1 # 2-3" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Barrio</label>
                <input type="text" value="Centro" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Ciudad</label>
                <input type="text" value="Bogotá" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Cantidad de servicios</label>
                <input type="number" value="1" min="0" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tipo de servicio</label>
                <select class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
                    <option selected>Ronda</option>
                    <option>Portería</option>
                    <option>Ocasional</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Horario</label>
                <select class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
                    <option selected>12H</option>
                    <option>8H</option>
                    <option>Ocasional</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Número de cuadrante</label>
                <input type="text" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
            </div>

            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700">Logo / Imagen</label>
                <input type="file" class="mt-2 block w-full rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
            </div>
        </div>

        <!-- Botones -->
        <div class="flex items-center justify-end gap-4 mt-6">
            <button type="submit" class="bg-[var(--primary)] text-white px-6 py-3 rounded-lg shadow-md hover:bg-[var(--primary)]/90 transition-all">Actualizar</button>
            <a href="{{ route('company.clients.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">Cancelar</a>
        </div>
    </form>
</div>
@endsection

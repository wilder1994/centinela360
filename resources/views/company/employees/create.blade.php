@extends('layouts.company')

@section('content')
<div class="animate-fadeIn space-y-8 max-w-6xl mx-auto px-6 relative">
    <!-- Fondo de la vista con un degradado vertical aplicando ambos colores corporativos -->
    <div class="absolute inset-0 -z-10 bg-gradient-to-b from-[var(--primary)] to-[var(--secondary)] opacity-80"></div>

    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-semibold text-gray-800">Nuevo empleado</h1>
        <a href="{{ route('company.employees.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Volver</a>
    </div>

    <!-- Formulario con un degradado suave y aplicando ambos colores corporativos -->
    <form class="bg-gradient-to-b from-[var(--primary)] to-[var(--secondary)] p-8 rounded-xl shadow-2xl space-y-8 mt-8 border border-gray-200 max-w-4xl mx-auto relative">
        <!-- Imagen de perfil -->
        <div class="flex justify-center mb-10">
            <label for="profile-picture" class="cursor-pointer rounded-full relative">
                <img src="{{ asset('images/default-avatar.png') }}" class="w-24 h-24 rounded-full border-4 border-white shadow-xl object-cover" alt="Imagen de perfil">
                <input type="file" id="profile-picture" class="absolute inset-0 opacity-0 cursor-pointer" />
            </label>
        </div>

        <!-- Información personal -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nombres completos</label>
                <input type="text" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Nombres completos" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Apellidos</label>
                <input type="text" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Apellidos" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tipo empleado</label>
                <select class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
                    <option>Guarda</option>
                    <option>Administrativo</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tipo documento</label>
                <select class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
                    <option>CC</option>
                    <option>CE</option>
                    <option>PAS</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Número documento</label>
                <input type="text" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Número documento" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">RH</label>
                <select class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
                    <option>O+</option>
                    <option>O-</option>
                    <option>A+</option>
                    <option>A-</option>
                    <option>B+</option>
                    <option>B-</option>
                    <option>AB+</option>
                    <option>AB-</option>
                </select>
            </div>
        </div>

        <!-- Información de contacto -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            <div>
                <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                <input type="text" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Teléfono" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Correo (opcional)</label>
                <input type="email" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="correo@ejemplo.com">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Dirección</label>
                <input type="text" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Dirección" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Fecha nacimiento</label>
                <input type="date" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Fecha ingreso</label>
                <input type="date" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Fecha vencimiento carnet</label>
                <input type="date" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
            </div>
        </div>

        <!-- Campo Puesto (cliente asignado al Guarda) -->
        <div class="md:col-span-3">
            <label class="block text-sm font-medium text-gray-700">Puesto (Cliente asignado)</label>
            <select class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
                <option>Cliente A</option>
                <option>Cliente B</option>
                <option>Cliente C</option>
                <option>Cliente D</option>
                <option>Cliente E</option>
            </select>
        </div>

        <!-- Estado -->
        <div class="md:col-span-3">
            <label class="block text-sm font-medium text-gray-700">Estado</label>
            <select class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
                <option>Activo</option>
                <option>En vacaciones</option>
                <option>Incapacitado</option>
                <option>Desprogramado</option>
                <option>Calamidad</option>
                <option>Despedido</option>
            </select>
        </div>

        <!-- Contacto de emergencia -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            <div>
                <label class="block text-sm font-medium text-gray-700">Contacto emergencia (nombre)</label>
                <input type="text" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Nombre de contacto emergencia" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Teléfono emergencia</label>
                <input type="text" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Teléfono de emergencia" required>
            </div>
        </div>

        <!-- Observaciones -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Observaciones</label>
            <textarea class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" rows="4" placeholder="Observaciones"></textarea>
        </div>

        <!-- Botones -->
        <div class="flex items-center justify-end gap-4 mt-10">
            <button type="submit" class="bg-[var(--primary)] text-white px-6 py-3 rounded-lg shadow-md hover:bg-[var(--primary)]/90 transition-all">Guardar</button>
            <a href="{{ route('company.employees.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">Cancelar</a>
        </div>
    </form>
</div>
@endsection

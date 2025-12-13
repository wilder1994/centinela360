@extends('layouts.company')

@section('content')
<div class="text-white space-y-4">
    {{-- Botones de acciones principales --}}
    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 text-gray-300">
        <div class="flex flex-wrap items-center justify-center gap-3">
            <button id="btn-turno-create" class="px-4 py-2 bg-primary text-white rounded-md shadow-sm hover:brightness-110 transition">Crear turno</button>
            <button id="btn-turno-edit" class="px-4 py-2 bg-gray-800 text-gray-200 border border-gray-700 rounded-md hover:bg-gray-700 transition">Editar turno</button>
            <button class="px-4 py-2 bg-rose-600/80 text-white rounded-md shadow-sm hover:bg-rose-600 transition">Eliminar turno</button>
            <button class="px-4 py-2 bg-emerald-600/90 text-white rounded-md shadow-sm hover:bg-emerald-600 transition">Crear malla</button>
            <button class="px-4 py-2 bg-amber-600/90 text-white rounded-md shadow-sm hover:bg-amber-600 transition">Eliminar malla</button>
        </div>

        {{-- Tarjetas en fila con tablas (placeholder) --}}
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3">
            @for ($i = 1; $i <= 5; $i++)
                <div class="bg-gray-800/60 border border-gray-700 rounded-md px-4 py-3 w-full">
                    <table class="w-full text-xs text-gray-300 border border-gray-700/60 rounded-md overflow-hidden">
                        <tbody>
                            <tr class="bg-gray-900/80 border-b border-gray-700/50">
                                <td class="px-2 py-2 text-white font-semibold uppercase tracking-wide">Turno</td>
                                <td class="px-2 py-2 text-white font-semibold uppercase tracking-wide">Descripcion</td>
                            </tr>
                            @for ($row = 2; $row <= 5; $row++)
                                <tr class="{{ $row % 2 === 0 ? 'bg-gray-800/80' : 'bg-gray-800/40' }} border-b border-gray-700/50 last:border-none">
                                    <td class="px-2 py-2 text-gray-400">Fila {{ $row - 1 }}</td>
                                    <td class="px-2 py-2 text-white/80">Columna 2</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            @endfor
        </div>
    </div>
</div>

{{-- Modal para formulario de turnos --}}
<div id="turno-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/70">
    <div class="bg-gray-900 border border-gray-800 rounded-lg shadow-2xl w-full max-w-xl mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-gray-400">Turnos</p>
                <h3 id="turno-form-title" class="text-white text-lg font-semibold">Crear turno</h3>
            </div>
            <button id="btn-turno-close" class="text-gray-400 hover:text-white text-xl">&times;</button>
        </div>
        @include('company.programming.partials.turno-form')
    </div>
</div>

<script>
// Control básico del modal de turnos (abrir/cerrar según botón)
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('turno-modal');
    const title = document.getElementById('turno-form-title');
    const btnCreate = document.getElementById('btn-turno-create');
    const btnEdit = document.getElementById('btn-turno-edit');
    const btnClose = document.getElementById('btn-turno-close');

    const openModal = (mode) => {
        if (!modal) return;
        title.textContent = mode === 'edit' ? 'Editar turno' : 'Crear turno';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };
    const closeModal = () => {
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    btnCreate?.addEventListener('click', (e) => { e.preventDefault(); openModal('create'); });
    btnEdit?.addEventListener('click', (e) => { e.preventDefault(); openModal('edit'); });
    btnClose?.addEventListener('click', (e) => { e.preventDefault(); closeModal(); });
    modal?.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
});
</script>
@endsection

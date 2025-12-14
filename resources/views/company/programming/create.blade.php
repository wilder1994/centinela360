@extends('layouts.company')

@section('content')
@include('company.programming.partials.header')

{{-- Acciones principales de turnos/mallas --}}
<div class="space-y-6">
    <div class="p-0 text-gray-800">
        <div class="flex flex-wrap items-center justify-center gap-3">
            <button id="btn-turno-create" class="px-4 py-1 rounded-full border border-[var(--primary)] text-[var(--primary)] bg-white shadow-[0_5px_18px_-8px_var(--primary)] hover:bg-[var(--primary)]/5 transition">Crear turno</button>
            <button id="btn-turno-edit" class="px-4 py-1 rounded-full border border-[var(--secondary)] text-[var(--secondary)] bg-white shadow-[0_5px_18px_-8px_var(--secondary)] hover:bg-[var(--secondary)]/5 transition">Editar turno</button>
            <button class="px-4 py-1 rounded-full border border-[var(--primary)]/70 text-[var(--primary)]/80 bg-white shadow-[0_5px_18px_-8px_var(--primary)] hover:bg-[var(--primary)]/10 transition">Eliminar turno</button>
            <button class="px-4 py-1 rounded-full border border-[var(--primary)] text-[var(--primary)] bg-white shadow-[0_5px_18px_-8px_var(--primary)] hover:bg-[var(--primary)]/5 transition">Crear malla</button>
            <button class="px-4 py-1 rounded-full border border-[var(--secondary)] text-[var(--secondary)] bg-white shadow-[0_5px_18px_-8px_var(--secondary)] hover:bg-[var(--secondary)]/5 transition">Eliminar malla</button>
        </div>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3">
            @for ($i = 1; $i <= 5; $i++)
                <div class="bg-white border border-[var(--primary)]/40 rounded-xl p-3 shadow-[0_6px_18px_-10px_var(--primary)]">
                    <table class="w-full text-xs text-gray-700 border border-[var(--primary)]/30 rounded-lg overflow-hidden">
                        <tbody>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <td class="px-2 py-2 text-gray-800 font-semibold uppercase tracking-wide">Turno</td>
                                <td class="px-2 py-2 text-gray-800 font-semibold uppercase tracking-wide">Descripcion</td>
                            </tr>
                            @for ($row = 2; $row <= 5; $row++)
                                <tr class="{{ $row % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} border-b border-gray-200 last:border-none">
                                    <td class="px-2 py-2 text-gray-600">Fila {{ $row - 1 }}</td>
                                    <td class="px-2 py-2 text-gray-800">Columna 2</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            @endfor
        </div>
    </div>
    {{-- malla de programacion --}}
    <div class="overflow-x-auto mt-8">
        <table class="min-w-full text-sm text-gray-700 border border-black overflow-hidden" style="table-layout: fixed;">
            <colgroup>
                @for ($c = 1; $c <= 33; $c++)
                    @if ($c === 1)
                        <col style="width:7%;">
                    @elseif ($c === 2)
                        <col style="width:15%;">
                    @else
                        <col style="width:2.3%;">
                    @endif
                @endfor
            </colgroup>
            <tbody>
                @for ($i = 1; $i <= 5; $i++)
                    <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} border-b border-gray-200 last:border-none">
                        @for ($c = 1; $c <= 33; $c++)
                            @if ($c === 1)
                                @if ($i === 1)
                                    <td class="px-2 py-1 border border-black text-transparent bg-white" rowspan="3">&nbsp;</td>
                                @elseif ($i <= 3)
                                    @continue
                                @else
                                    <td class="px-2 py-1 border border-black text-transparent bg-white">&nbsp;</td>
                                @endif
                            @elseif ($c === 2)
                                @if ($i === 1)
                                    <td class="px-2 py-1 border border-black text-transparent bg-white" rowspan="3">&nbsp;</td>
                                @elseif ($i <= 3)
                                    @continue
                                @else
                                    <td class="px-2 py-1 border border-black text-transparent bg-white">&nbsp;</td>
                                @endif
                            @elseif ($i === 1 && $c === 3)
                                <td class="px-2 py-1 border border-black text-transparent bg-white" colspan="31">&nbsp;</td>
                                @break
                            @elseif ($i === 2 && $c === 3)
                                <td class="px-2 py-1 border border-black text-transparent bg-white" colspan="31">&nbsp;</td>
                                @break
                            @else
                                <td class="px-2 py-1 border border-black text-transparent bg-white">&nbsp;</td>
                            @endif
                        @endfor
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>

{{-- Modal para formulario de turnos --}}
<div id="turno-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60">
    <div class="bg-white border border-gray-200 rounded-xl shadow-2xl w-full max-w-xl mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-gray-500">Turnos</p>
                <h3 id="turno-form-title" class="text-gray-900 text-lg font-semibold">Crear turno</h3>
            </div>
            <button id="btn-turno-close" class="text-gray-500 hover:text-gray-800 text-xl">&times;</button>
        </div>
        @include('company.programming.partials.turno-form')
    </div>
</div>

<script>
// Control basico del modal de turnos (abrir/cerrar segun boton)
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

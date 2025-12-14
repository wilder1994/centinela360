@extends('layouts.company')

@section('content')
@include('company.programming.partials.header')

@php
    $company = $company ?? auth()->user()->company;
    $clients = $clients ?? collect();
    $turns = $turns ?? collect();
@endphp

{{-- Acciones principales de turnos/mallas --}}
<div class="space-y-6">
    @if (session('status'))
        <div class="px-4 py-3 rounded-lg border border-green-200 bg-green-50 text-green-800 text-sm">
            {{ session('status') }}
        </div>
    @endif
    @if (session('error'))
        <div class="px-4 py-3 rounded-lg border border-red-200 bg-red-50 text-red-800 text-sm">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="px-4 py-3 rounded-lg border border-red-200 bg-red-50 text-red-800 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="p-0 text-gray-800">
        <div class="flex flex-wrap items-center justify-center gap-3">
            <button id="btn-turno-create" class="px-4 py-1 rounded-full border border-[var(--primary)] text-[var(--primary)] bg-white shadow-[0_5px_18px_-8px_var(--primary)] hover:bg-[var(--primary)]/5 transition">Crear turno</button>
            <button id="btn-turno-edit" class="px-4 py-1 rounded-full border border-[var(--secondary)] text-[var(--secondary)] bg-white shadow-[0_5px_18px_-8px_var(--secondary)] hover:bg-[var(--secondary)]/5 transition">Editar turno</button>
            <button class="px-4 py-1 rounded-full border border-[var(--primary)]/70 text-[var(--primary)]/80 bg-white shadow-[0_5px_18px_-8px_var(--primary)] hover:bg-[var(--primary)]/10 transition">Eliminar turno</button>
            <button class="px-4 py-1 rounded-full border border-[var(--primary)] text-[var(--primary)] bg-white shadow-[0_5px_18px_-8px_var(--primary)] hover:bg-[var(--primary)]/5 transition">Crear malla</button>
            <button class="px-4 py-1 rounded-full border border-[var(--secondary)] text-[var(--secondary)] bg-white shadow-[0_5px_18px_-8px_var(--secondary)] hover:bg-[var(--secondary)]/5 transition">Eliminar malla</button>
        </div>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3">
            @php
                $chunks = ($turns ?? collect())->chunk(4);
                $totalCards = max(5, $chunks->count());
            @endphp
            @for ($card = 0; $card < 5; $card++)
                @php $rows = $chunks[$card] ?? collect(); @endphp
                <div class="bg-white border border-[var(--primary)]/40 rounded-xl p-3 shadow-[0_6px_18px_-10px_var(--primary)]">
                    <table class="w-full text-xs text-gray-700 border border-[var(--primary)]/30 rounded-lg overflow-hidden">
                        <tbody>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <td class="px-2 py-2 text-gray-800 font-semibold uppercase tracking-wide">Turno</td>
                                <td class="px-2 py-2 text-gray-800 font-semibold uppercase tracking-wide">Descripcion</td>
                            </tr>
                            @foreach ($rows as $row)
                                <tr class="border-b border-gray-200 last:border-none" style="background-color: {{ $row->color }}1f;">
                                    <td class="px-2 py-2 text-gray-800 font-semibold">{{ $row->name }}</td>
                                    <td class="px-2 py-2 text-gray-800">{{ $row->description }}</td>
                                </tr>
                            @endforeach
                            @if ($rows->isEmpty())
                                <tr class="border-b border-gray-200 last:border-none">
                                    <td class="px-2 py-2 text-gray-400 italic">Sin turnos</td>
                                    <td class="px-2 py-2 text-gray-400 italic">---</td>
                                </tr>
                            @endif
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
                        <col style="width:10%;">
                    @else
                        <col style="width:2.7%;">
                    @endif
                @endfor
            </colgroup>
            <tbody>
                {{-- Fila 1 --}}
                <tr class="bg-gray-50 border-b border-gray-200">
                    {{-- Col 1 rowspan 3 con logo --}}
                    <td class="px-2 py-1 border border-black bg-white" rowspan="3">
                        <div class="flex items-center justify-center">
                            <img src="{{ $company->logo ? asset('storage/' . $company->logo) : asset('images/default-logo.png') }}" alt="Logo" class="max-h-12 object-contain">
                        </div>
                    </td>
                    {{-- Col 2 rowspan 3 vacio --}}
                    <td class="px-2 py-1 border border-black bg-white" rowspan="3">&nbsp;</td>
                    {{-- Col 3-33 combinado con select de clientes --}}
                    <td class="px-2 py-1 border border-black bg-white" colspan="31">
                        <select class="w-full h-full text-sm border border-gray-300 rounded-md">
                            <option value="">Seleccione cliente</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->business_name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                {{-- Fila 2 (col 3-33 combinadas vacias) --}}
                <tr class="bg-white border-b border-gray-200">
                    <td class="px-2 py-1 border border-black bg-white" colspan="31">&nbsp;</td>
                </tr>
                {{-- Fila 3 (celdas individuales) --}}
                <tr class="bg-gray-50 border-b border-gray-200">
                    @for ($c = 3; $c <= 33; $c++)
                        <td class="px-2 py-1 border border-black text-transparent bg-white">&nbsp;</td>
                    @endfor
                </tr>
                {{-- Fila 4 (celdas individuales) --}}
                <tr class="bg-white border-b border-gray-200">
                    @for ($c = 1; $c <= 33; $c++)
                        <td class="px-2 py-1 border border-black text-transparent bg-white">&nbsp;</td>
                    @endfor
                </tr>
                {{-- Fila 5 (select de turnos en col 3-33) --}}
                <tr class="bg-gray-50 border-b border-gray-200">
                    @for ($c = 1; $c <= 33; $c++)
                        @if ($c <= 2)
                            <td class="px-2 py-1 border border-black text-transparent bg-white">&nbsp;</td>
                        @else
                            <td class="px-2 py-1 border border-black bg-white" data-turn-cell>
                                <select class="w-full h-full text-sm border border-gray-300 rounded-md turn-selector">
                                    <option value="">Seleccione turno</option>
                                    @foreach ($turns as $turn)
                                        <option value="{{ $turn->id }}" data-color="{{ $turn->color }}">{{ $turn->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        @endif
                    @endfor
                </tr>
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
        <form method="POST" action="{{ route('company.programming.store') }}">
            @csrf
            @include('company.programming.partials.turno-form')
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" id="btn-turno-cancel" class="px-4 py-2 rounded-full border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded-full border border-[var(--primary)] text-white bg-[var(--primary)] hover:brightness-110">Guardar</button>
            </div>
        </form>
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
    const btnCancel = document.getElementById('btn-turno-cancel');
    const turnSelectors = document.querySelectorAll('.turn-selector');

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
    btnCancel?.addEventListener('click', (e) => { e.preventDefault(); closeModal(); });
    modal?.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

    // Pintar celdas según turno seleccionado
    turnSelectors.forEach((sel) => {
        sel.addEventListener('change', () => {
            const opt = sel.selectedOptions[0];
            const color = opt?.dataset?.color || '';
            const cell = sel.closest('td');
            if (cell) {
                cell.style.backgroundColor = color ? color + '33' : '#ffffff';
            }
        });
    });
});
</script>
@endsection

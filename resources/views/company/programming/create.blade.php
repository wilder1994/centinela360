@extends('layouts.company')

@section('content')
@include('company.programming.partials.header')

@php
    $company = $company ?? auth()->user()->company;
    $clients = $clients ?? collect();
    $turns = $turns ?? collect();
    $employees = $employees ?? collect();
    $clientServices = ($clients ?? collect())->flatMap(function ($client) {
        $services = $client->services ?? collect();
        if ($services->isEmpty()) {
            return [[
                'client_id' => $client->id,
                'name' => $client->business_name,
                'service_type' => null,
                'service_schedule' => null,
                'label' => $client->business_name,
            ]];
        }

        return $services->map(function ($service) use ($client) {
            $serviceType = $service->service_type ?? '';
            $serviceSchedule = $service->service_schedule ?? '';
            $label = trim($client->business_name . ' - ' . $serviceType . ' ' . $serviceSchedule);
            return [
                'client_id' => $client->id,
                'name' => $client->business_name,
                'service_type' => $serviceType,
                'service_schedule' => $serviceSchedule,
                'label' => $label,
            ];
        });
    })->values();
    $turnData = $turns->map(function ($t) {
        return [
            'id' => $t->id,
            'name' => $t->name,
            'description' => $t->description,
            'color' => $t->color,
        ];
    });
    $employeesByClient = $employees
        ->groupBy('client_id')
        ->map(fn ($items) => $items->map(fn ($e) => [
            'id' => $e->id,
            'name' => trim(($e->first_name ?? '') . ' ' . ($e->last_name ?? '')),
            'document' => $e->document_number,
        ])->values())
        ->toArray();
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
            <button id="btn-turno-delete" class="px-4 py-1 rounded-full border border-[var(--primary)]/70 text-[var(--primary)]/80 bg-white shadow-[0_5px_18px_-8px_var(--primary)] hover:bg-[var(--primary)]/10 transition">Eliminar turno</button>
            <button class="px-4 py-1 rounded-full border border-[var(--primary)] text-[var(--primary)] bg-white shadow-[0_5px_18px_-8px_var(--primary)] hover:bg-[var(--primary)]/5 transition">Crear malla</button>
            <button class="px-4 py-1 rounded-full border border-[var(--secondary)] text-[var(--secondary)] bg-white shadow-[0_5px_18px_-8px_var(--secondary)] hover:bg-[var(--secondary)]/5 transition">Eliminar malla</button>
            <button id="btn-calendar" class="px-4 py-1 rounded-full border border-[var(--primary)] text-white bg-[var(--primary)] shadow-[0_5px_18px_-8px_var(--primary)] hover:brightness-110 transition">Calendario</button>
            <button id="btn-save-grid" class="px-4 py-1 rounded-full border border-[var(--primary)] text-white bg-[var(--primary)] shadow-[0_5px_18px_-8px_var(--primary)] hover:brightness-110 transition">Guardar malla</button>
        </div>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3">
            @php
                $chunks = ($turns ?? collect())->chunk(4);
            @endphp

            @forelse ($chunks as $rows)
                @if ($rows->isEmpty())
                    @continue
                @endif
                <div class="bg-white border border-[var(--primary)]/40 rounded-xl p-3 shadow-[0_6px_18px_-10px_var(--primary)]">
                    <table class="w-full text-xs text-gray-700 border border-[var(--primary)]/30 rounded-lg overflow-hidden">
                        <tbody>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <td class="px-2 py-2 text-gray-800 font-semibold uppercase tracking-wide">Turno</td>
                                <td class="px-2 py-2 text-gray-800 font-semibold uppercase tracking-wide">Descripcion</td>
                            </tr>
                            @foreach ($rows as $row)
                                <tr class="border-b border-gray-200 last:border-none" style="background-color: {{ $row->color }};">
                                    <td class="px-2 py-2 text-gray-800 font-semibold">{{ $row->name }}</td>
                                    <td class="px-2 py-2 text-gray-800">{{ $row->description }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 text-sm py-6 border border-dashed border-gray-200 rounded-xl bg-white">
                    No hay turnos creados.
                </div>
            @endforelse
        </div>
    </div>
    {{-- malla de programacion --}}
    <div class="overflow-x-auto mt-8">
        <table class="programming-table min-w-full text-sm text-gray-700 border border-black overflow-hidden" style="table-layout: fixed;">
            <colgroup>
                @for ($c = 1; $c <= 33; $c++)
                    @if ($c === 1)
                        <col style="width:10%;">
                    @elseif ($c === 2)
                        <col style="width:15%;">
                    @else
                        <col style="width:2.42%;">
                    @endif
                @endfor
            </colgroup>
            <tbody>
                {{-- Fila 1 --}}
                <tr class="primary-row border-b border-gray-200">
                    {{-- Col 1 rowspan 3 con logo --}}
                    <td class="px-2 py-1 border border-black" rowspan="3">
                        <div class="flex items-center justify-center">
                            <img src="{{ $company->logo ? asset('storage/' . $company->logo) : asset('images/default-logo.png') }}" alt="Logo" class="max-h-12 object-contain">
                        </div>
                    </td>
                    {{-- Col 2 rowspan 3 vacio --}}
                    <td class="px-2 py-1 border border-black" rowspan="3">&nbsp;</td>
                    {{-- Col 3-33 combinado con campo de clientes --}}
                    <td class="px-2 py-1 border border-black" colspan="31">
                        <input id="client-input" class="client-input w-full h-full text-center" autocomplete="off" placeholder="Seleccione cliente" />
                    </td>
                </tr>
                {{-- Fila 2 (col 3-33 combinadas) --}}
                <tr class="primary-row border-b border-gray-200">
                    <td class="px-2 py-1 border border-black text-center font-semibold" colspan="31" data-month-title>&nbsp;</td>
                </tr>
                {{-- Fila 3 (celdas individuales) --}}
                <tr class="primary-row border-b border-gray-200">
                    @for ($c = 3; $c <= 33; $c++)
                        <td class="day-cell border border-black text-center" data-day-name>&nbsp;</td>
                    @endfor
                </tr>
                {{-- Fila 4 (celdas individuales) --}}
                <tr class="primary-row border-b border-gray-200">
                    @for ($c = 1; $c <= 33; $c++)
                        @if ($c === 1)
                            <td class="px-2 py-1 border border-black text-center font-semibold">Cedula</td>
                        @elseif ($c === 2)
                            <td class="px-2 py-1 border border-black text-center font-semibold">Nombre</td>
                        @else
                            <td class="day-cell border border-black text-center" data-day-number>&nbsp;</td>
                        @endif
                    @endfor
                </tr>
                {{-- Fila 5 (turnos en col 3-33, editable) --}}
                <tr class="bg-gray-50 border-b border-gray-200">
                    @for ($c = 1; $c <= 33; $c++)
                        @if ($c === 1)
                            <td class="px-2 py-1 border border-black bg-white text-center text-gray-800" data-employee-doc>&nbsp;</td>
                        @elseif ($c === 2)
                            <td class="px-2 py-1 border border-black bg-white">
                                <select id="employee-select" class="employee-select" disabled>
                                    <option value="" selected>Seleccione cliente</option>
                                </select>
                            </td>
                        @else
                            <td class="border border-black bg-white p-0" data-turn-cell>
                                <input class="turn-input block w-full h-full text-center" autocomplete="off" placeholder="" />
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
        <form id="turno-form" method="POST" action="{{ route('company.programming.store') }}" data-update-template="{{ route('company.programming.update', ['turn' => '__ID__']) }}">
            @csrf
            <input type="hidden" name="_method" value="POST" id="turno-method">
            <input type="hidden" name="turn_id" id="turn-id">
            @include('company.programming.partials.turno-form')
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" id="btn-turno-cancel" class="px-4 py-2 rounded-full border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded-full border border-[var(--primary)] text-white bg-[var(--primary)] hover:brightness-110">Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal calendario --}}
<div id="calendar-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60">
    <div class="bg-white border border-gray-200 rounded-xl shadow-2xl w-full max-w-sm mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-gray-500">Malla de programación</p>
                <h3 class="text-gray-900 text-lg font-semibold">Seleccionar mes y año</h3>
            </div>
            <button id="btn-calendar-close" class="text-gray-500 hover:text-gray-800 text-xl">&times;</button>
        </div>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Mes</label>
                <select id="calendar-month" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm bg-white focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                    @foreach ([
                        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                    ] as $num => $label)
                        <option value="{{ $num }}" @selected($num === now()->month)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Año</label>
                <input id="calendar-year" type="number" min="1900" max="2100" value="{{ now()->year }}" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-[var(--primary)] focus:ring-[var(--primary)]">
            </div>
            <div class="flex justify-end gap-2">
                <button id="btn-calendar-cancel" class="px-4 py-2 rounded-full border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">Cancelar</button>
                <button id="btn-calendar-save" class="px-4 py-2 rounded-full border border-[var(--primary)] text-white bg-[var(--primary)] hover:brightness-110">Guardar</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal para seleccionar turno (editar/eliminar) --}}
<div id="turno-list-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60">
    <div class="bg-white border border-gray-200 rounded-xl shadow-2xl w-full max-w-md mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-gray-500" id="turno-list-subtitle">Turnos</p>
                <h3 id="turno-list-title" class="text-gray-900 text-lg font-semibold">Selecciona un turno</h3>
            </div>
            <button id="btn-turno-list-close" class="text-gray-500 hover:text-gray-800 text-xl">&times;</button>
        </div>
        <div id="turno-list-container" class="max-h-64 overflow-y-auto divide-y divide-gray-100">
            <!-- Opciones de turnos se renderizan por JS -->
        </div>
        <div class="mt-4 flex justify-end gap-2">
            <button type="button" id="btn-turno-list-cancel" class="px-4 py-2 rounded-full border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">Cancelar</button>
            <button type="button" id="btn-turno-list-confirm" class="px-4 py-2 rounded-full border border-[var(--primary)] text-white bg-[var(--primary)] hover:brightness-110 disabled:opacity-60 disabled:cursor-not-allowed" disabled>Continuar</button>
        </div>
    </div>
</div>

{{-- Formulario oculto para eliminar --}}
<form id="turno-delete-form" method="POST" class="hidden" data-delete-template="{{ route('company.programming.destroy', ['turn' => '__ID__']) }}">
    @csrf
    @method('DELETE')
</form>

<script>
// Estilos en línea para los campos de turno/cliente y los dropdowns
const turnInputStyle = document.createElement('style');
turnInputStyle.textContent = `
    .turn-input {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding: 0;
        margin: 0;
        border: none;
        outline: none;
        background: transparent;
        height: 100%;
        min-height: 0;
        line-height: 1.1;
        font-size: 13px;
        font-weight: 600;
        color: #1f2937;
        text-align: center;
        width: 100%;
        text-transform: uppercase;
    }
    .turn-dropdown {
        position: absolute;
        z-index: 60;
        background: #fff;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        max-height: 200px;
        overflow-y: auto;
        min-width: 120px;
    }
    .turn-dropdown button {
        display: block;
        width: 100%;
        text-align: left;
        padding: 8px 10px;
        background: transparent;
        border: none;
        font-size: 13px;
        color: #111827;
        cursor: pointer;
    }
    .turn-dropdown button:hover {
        background: #f3f4f6;
    }
    .client-input {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding: 0 6px;
        margin: 0;
        border: none;
        outline: none;
        background: transparent;
        height: 100%;
        width: 100%;
        text-align: center;
        font-size: 14px;
        color: #111827;
    }
    .employee-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background: transparent;
        border: none;
        outline: none;
        width: 100%;
        height: 100%;
        min-height: 0;
        padding: 0 4px;
        font-size: 13px;
        line-height: 1.1;
        color: #111827;
        display: block;
    }
    .employee-select:disabled {
        color: #9ca3af;
    }
    /* Oculta el ícono de desplegable en navegadores IE/Edge heredados */
    select.employee-select::-ms-expand {
        display: none;
    }
    .day-cell {
        padding: 2px 0;
        line-height: 1.1;
        font-size: 12px;
        min-height: 22px;
    }
    .programming-table td {
        height: 28px;
        padding-top: 2px;
        padding-bottom: 2px;
    }
    .programming-table .primary-row td {
        background: var(--primary) !important;
        color: #fff !important;
    }
    .programming-table .primary-row td * {
        color: inherit;
    }
    .programming-table .primary-row .client-input {
        color: #fff;
        font-weight: 700;
    }
    .programming-table .primary-row .client-input::placeholder {
        color: rgba(255, 255, 255, 0.85);
    }
    .programming-table .primary-row [data-day-name],
    .programming-table .primary-row [data-day-number] {
        color: #fff;
    }
    .programming-table .primary-row .day-weekend {
        color: #f97316 !important;
    }
    .turn-selected {
        outline: 2px solid var(--primary);
        outline-offset: -2px;
        box-shadow: inset 0 0 0 1px rgba(0,0,0,0.05);
        background: rgba(14, 165, 233, 0.12);
    }
    .day-weekend {
        color: #f97316 !important;
        font-weight: 700;
    }
`;
document.head.appendChild(turnInputStyle);

// Control de turnos: crear/editar/eliminar y pintar celdas
document.addEventListener('DOMContentLoaded', () => {
    const turns = @json($turnData);
    const clients = @json($clientServices);
    const employeesByClient = @json($employeesByClient);
    const dayNameCells = Array.from(document.querySelectorAll('[data-day-name]'));
    const dayNumberCells = Array.from(document.querySelectorAll('[data-day-number]'));
    const monthTitleCell = document.querySelector('[data-month-title]');
    const turnInputs = document.querySelectorAll('.turn-input');
    const turnInputList = Array.from(turnInputs);
    let selection = new Set();
    let anchorIndex = null;
    let copyBuffer = [];
    let lastIndex = null;
    const findTurnByName = (val) => {
        const value = (val || '').toUpperCase();
        return turns.find(t => (t.name || '').toUpperCase() === value);
    };
    const setTurnValue = (input, value, silent = false) => {
        const cell = input?.closest('td');
        const upper = (value || '').toUpperCase();
        input.value = upper;
        const match = findTurnByName(upper);
        if (match && cell) {
            cell.style.backgroundColor = match.color || '#ffffff';
            return true;
        }
        if (cell) cell.style.backgroundColor = '#ffffff';
        if (!silent && upper) {
            alert('Este valor no existe, selecciona uno que exista o créalo.');
            input.value = '';
        } else if (!match) {
            input.value = '';
        }
        return false;
    };
    const clearSelection = () => {
        selection.clear();
        anchorIndex = null;
        lastIndex = null;
        turnInputList.forEach((input) => {
            const cell = input.closest('td');
            if (cell) cell.classList.remove('turn-selected');
        });
    };
    const applySelection = () => {
        turnInputList.forEach((input) => {
            const cell = input.closest('td');
            if (!cell) return;
            const idx = Number(input.dataset.turnIndex);
            if (selection.has(idx)) cell.classList.add('turn-selected');
            else cell.classList.remove('turn-selected');
        });
    };
    const setSelectionRange = (start, end, anchor = null) => {
        const s = Math.max(0, Math.min(start, end));
        const e = Math.min(turnInputList.length - 1, Math.max(start, end));
        selection = new Set();
        for (let i = s; i <= e; i += 1) selection.add(i);
        anchorIndex = anchor ?? s;
        lastIndex = anchorIndex;
        applySelection();
    };
    const moveFocus = (currentIdx, delta, extend = false) => {
        const targetIdx = Math.min(Math.max(currentIdx + delta, 0), turnInputList.length - 1);
        const target = turnInputList[targetIdx];
        if (!target) return;
        if (extend && anchorIndex !== null) {
            setSelectionRange(anchorIndex, targetIdx, anchorIndex);
        } else {
            setSelectionRange(targetIdx, targetIdx, targetIdx);
        }
        target.focus();
        target.select();
    };

    const modal = document.getElementById('turno-modal');
    const title = document.getElementById('turno-form-title');
    const btnCreate = document.getElementById('btn-turno-create');
    const btnEdit = document.getElementById('btn-turno-edit');
    const btnDelete = document.getElementById('btn-turno-delete');
    const btnClose = document.getElementById('btn-turno-close');
    const btnCancel = document.getElementById('btn-turno-cancel');

    const form = document.getElementById('turno-form');
    const methodInput = document.getElementById('turno-method');
    const turnIdInput = document.getElementById('turn-id');
    const nameInput = document.getElementById('turn-name');
    const descInput = document.getElementById('turn-description');
    const colorInput = document.getElementById('turn-color');
    const updateTemplate = form?.dataset?.updateTemplate || '';
    const defaultAction = form?.getAttribute('action') || '';

    const listModal = document.getElementById('turno-list-modal');
    const listContainer = document.getElementById('turno-list-container');
    const listTitle = document.getElementById('turno-list-title');
    const listSubtitle = document.getElementById('turno-list-subtitle');
    const btnListClose = document.getElementById('btn-turno-list-close');
    const btnListCancel = document.getElementById('btn-turno-list-cancel');
    const btnListConfirm = document.getElementById('btn-turno-list-confirm');
    const deleteForm = document.getElementById('turno-delete-form');
    const deleteTemplate = deleteForm?.dataset?.deleteTemplate || '';

    // Dropdown flotante para inputs de turno
    const dropdown = document.createElement('div');
    dropdown.className = 'turn-dropdown hidden';
    document.body.appendChild(dropdown);

    let dropdownTarget = null;

    // Dropdown para clientes
    const clientDropdown = document.createElement('div');
    clientDropdown.className = 'turn-dropdown hidden';
    document.body.appendChild(clientDropdown);
    const clientInput = document.getElementById('client-input');
    const employeeSelect = document.getElementById('employee-select');
    const employeeDocCell = document.querySelector('[data-employee-doc]');
    let selectedClientId = null;

    // Calendario
    const btnCalendar = document.getElementById('btn-calendar');
    const calendarModal = document.getElementById('calendar-modal');
    const calendarMonth = document.getElementById('calendar-month');
    const calendarYear = document.getElementById('calendar-year');
    const btnCalendarClose = document.getElementById('btn-calendar-close');
    const btnCalendarCancel = document.getElementById('btn-calendar-cancel');
    const btnCalendarSave = document.getElementById('btn-calendar-save');
    const dayLabels = ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'];
    const monthLabels = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    const companyName = @json($company->name ?? $company->business_name ?? 'Empresa');

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

    const resetForm = () => {
        if (!form) return;
        form.setAttribute('action', defaultAction);
        if (methodInput) methodInput.value = 'POST';
        if (turnIdInput) turnIdInput.value = '';
        if (nameInput) nameInput.value = '';
        if (descInput) descInput.value = '';
        if (colorInput) colorInput.value = '#22d3ee';
    };

    const setFormForEdit = (turn) => {
        if (!form || !turn) return;
        form.setAttribute('action', updateTemplate.replace('__ID__', turn.id));
        if (methodInput) methodInput.value = 'PUT';
        if (turnIdInput) turnIdInput.value = turn.id;
        if (nameInput) nameInput.value = turn.name || '';
        if (descInput) descInput.value = turn.description || '';
        if (colorInput) colorInput.value = turn.color || '#22d3ee';
    };

    const closeListModal = () => {
        if (!listModal) return;
        listModal.classList.add('hidden');
        listModal.classList.remove('flex');
    };

    const renderTurnList = () => {
        if (!listContainer) return;
        if (!turns.length) {
            listContainer.innerHTML = '<p class="text-sm text-gray-500 py-2">No hay turnos creados.</p>';
            return;
        }
        const items = turns.map((t, idx) => `
            <label class="flex items-center gap-3 px-3 py-2 cursor-pointer hover:bg-gray-50">
                <input type="radio" name="turn-choice" value="${t.id}" class="text-[var(--primary)]" ${idx === 0 ? 'checked' : ''}>
                <div class="flex flex-col">
                    <span class="font-medium text-gray-800">${t.name}</span>
                    <span class="text-xs text-gray-500">${t.description || 'Sin descripción'}</span>
                </div>
                <span class="ml-auto w-4 h-4 rounded-full border border-gray-200" style="background:${t.color};"></span>
            </label>
        `).join('');
        listContainer.innerHTML = items;
    };

    const openListModal = (mode) => {
        if (!listModal) return;
        renderTurnList();
        btnListConfirm?.setAttribute('data-mode', mode);
        btnListConfirm.disabled = !turns.length;
        listTitle.textContent = mode === 'edit' ? 'Selecciona el turno a editar' : 'Selecciona el turno a eliminar';
        listSubtitle.textContent = mode === 'edit' ? 'Editar turno' : 'Eliminar turno';
        listModal.classList.remove('hidden');
        listModal.classList.add('flex');
    };

    btnCreate?.addEventListener('click', (e) => { e.preventDefault(); resetForm(); openModal('create'); });
    btnEdit?.addEventListener('click', (e) => { e.preventDefault(); openListModal('edit'); });
    btnDelete?.addEventListener('click', (e) => { e.preventDefault(); openListModal('delete'); });
    btnClose?.addEventListener('click', (e) => { e.preventDefault(); closeModal(); });
    btnCancel?.addEventListener('click', (e) => { e.preventDefault(); closeModal(); });
    modal?.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
    btnListClose?.addEventListener('click', (e) => { e.preventDefault(); closeListModal(); });
    btnListCancel?.addEventListener('click', (e) => { e.preventDefault(); closeListModal(); });
    listModal?.addEventListener('click', (e) => { if (e.target === listModal) closeListModal(); });

    btnListConfirm?.addEventListener('click', (e) => {
        e.preventDefault();
        const mode = btnListConfirm.dataset.mode;
        const selected = document.querySelector('input[name="turn-choice"]:checked');
        if (!selected) return;
        const turnId = Number(selected.value);
        const turn = turns.find(t => Number(t.id) === turnId);
        if (!turn) return;

        if (mode === 'edit') {
            resetForm();
            setFormForEdit(turn);
            closeListModal();
            openModal('edit');
        } else if (mode === 'delete') {
            if (!deleteForm) return;
            deleteForm.setAttribute('action', deleteTemplate.replace('__ID__', turn.id));
            closeListModal();
            deleteForm.submit();
        }
    });

    // Helpers para dropdown de turnos
    const hideDropdown = () => {
        dropdown.classList.add('hidden');
        dropdownTarget = null;
    };

    const showDropdown = (input) => {
        if (!input) return;
        dropdownTarget = input;
        dropdown.innerHTML = turns.map(t => `
            <button type="button" data-name="${t.name}">${t.name}</button>
        `).join('');
        const rect = input.getBoundingClientRect();
        dropdown.style.left = `${rect.left + window.scrollX}px`;
        dropdown.style.top = `${rect.bottom + window.scrollY + 4}px`;
        dropdown.style.minWidth = `${rect.width}px`;
        dropdown.classList.remove('hidden');
    };

    dropdown.addEventListener('mousedown', (e) => {
        const btn = e.target.closest('button[data-name]');
        if (!btn || !dropdownTarget) return;
        e.preventDefault();
        const name = btn.dataset.name.toUpperCase();
        dropdownTarget.value = name;
        const cell = dropdownTarget.closest('td');
        const match = turns.find(t => t.name.toUpperCase() === name);
        if (match && cell) cell.style.backgroundColor = match.color || '#ffffff';
        hideDropdown();
    });

    document.addEventListener('click', (e) => {
        if (dropdown.contains(e.target)) return;
        if (dropdownTarget && dropdownTarget.contains(e.target)) return;
        hideDropdown();
    });

    // Pintar celdas según turno escrito / seleccionado
    turnInputList.forEach((input, idx) => {
        input.dataset.turnIndex = idx;
        // Fuerza mayúsculas al escribir
        input.addEventListener('input', () => {
            input.value = (input.value || '').toUpperCase();
        });

        input.addEventListener('focus', () => {
            showDropdown(input);
            setSelectionRange(idx, idx, idx);
            lastIndex = idx;
        });

        input.addEventListener('click', (e) => {
            showDropdown(input);
            if (e.shiftKey && anchorIndex !== null) {
                setSelectionRange(anchorIndex, idx, anchorIndex);
            } else if (e.ctrlKey || e.metaKey) {
                if (selection.has(idx)) selection.delete(idx);
                else selection.add(idx);
                if (selection.size === 0) anchorIndex = null;
                applySelection();
            } else {
                setSelectionRange(idx, idx, idx);
            }
            lastIndex = idx;
        });

        input.addEventListener('change', () => {
            setTurnValue(input, input.value, false);
        });

        input.addEventListener('keydown', (e) => {
            const currentIdx = Number(input.dataset.turnIndex);
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                moveFocus(currentIdx, -1, e.shiftKey);
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                moveFocus(currentIdx, 1, e.shiftKey);
            } else if (e.key === 'Escape') {
                clearSelection();
            }
        });
    });

    // Client dropdown helpers
    const hideClientDropdown = () => {
        clientDropdown.classList.add('hidden');
    };

    const showClientDropdown = (input) => {
        if (!input) return;
        const term = (input.value || '').toLowerCase();
        const filtered = clients.filter(c => (c.label || '').toLowerCase().includes(term));
        clientDropdown.innerHTML = filtered.map(c => `
            <button type="button"
                data-client-id="${c.client_id}"
                data-label="${c.label}"
                data-service="${c.service_type || ''}"
                data-schedule="${c.service_schedule || ''}">
                ${c.label}
            </button>
        `).join('') || '<div class="px-3 py-2 text-sm text-gray-500">Sin coincidencias</div>';
        const rect = input.getBoundingClientRect();
        clientDropdown.style.left = `${rect.left + window.scrollX}px`;
        clientDropdown.style.top = `${rect.bottom + window.scrollY + 4}px`;
        clientDropdown.style.minWidth = `${rect.width}px`;
        clientDropdown.classList.remove('hidden');
    };

    const setEmployeeDoc = (value) => {
        if (!employeeDocCell) return;
        if (!value) {
            employeeDocCell.innerHTML = '&nbsp;';
            return;
        }
        employeeDocCell.textContent = value;
    };

    const renderEmployeeOptions = (clientId) => {
        if (!employeeSelect) return;

        selectedClientId = clientId || null;
        const key = clientId !== null ? String(clientId) : null;
        const list = key ? (employeesByClient[key] || []) : [];

        employeeSelect.innerHTML = '';
        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = clientId ? 'Selecciona empleado' : 'Seleccione cliente';
        placeholder.selected = true;
        if (clientId) {
            placeholder.disabled = true;
        }
        employeeSelect.appendChild(placeholder);
        setEmployeeDoc('');

        if (!clientId) {
            employeeSelect.setAttribute('disabled', 'disabled');
            return;
        }

        if (!list.length) {
            const emptyOpt = document.createElement('option');
            emptyOpt.value = '';
            emptyOpt.textContent = 'Sin empleados para este cliente';
            emptyOpt.disabled = true;
            employeeSelect.appendChild(emptyOpt);
            employeeSelect.setAttribute('disabled', 'disabled');
            return;
        }

        employeeSelect.removeAttribute('disabled');
        list.forEach(emp => {
            const opt = document.createElement('option');
            opt.value = emp.id;
            opt.textContent = emp.name;
            if (emp.document) opt.dataset.document = emp.document;
            employeeSelect.appendChild(opt);
        });
    };

    const syncClientFromInput = () => {
        if (!clientInput) return;
        const value = (clientInput.value || '').trim().toLowerCase();
        const match = clients.find(c => (c.label || '').toLowerCase() === value);
        if (match) {
            clientInput.dataset.clientId = match.client_id;
            clientInput.value = match.label;
            renderEmployeeOptions(match.client_id);
        } else {
            clientInput.dataset.clientId = '';
            renderEmployeeOptions(null);
        }
    };

    clientDropdown.addEventListener('mousedown', (e) => {
        const btn = e.target.closest('button[data-client-id]');
        if (!btn) return;
        e.preventDefault();
        const label = btn.dataset.label || '';
        const id = btn.dataset.clientId;
        clientInput.value = label;
        clientInput.dataset.clientId = id;
        renderEmployeeOptions(id);
        hideClientDropdown();
    });

    if (clientInput) {
        clientInput.addEventListener('focus', () => showClientDropdown(clientInput));
        clientInput.addEventListener('click', () => showClientDropdown(clientInput));
        clientInput.addEventListener('input', () => {
            showClientDropdown(clientInput);
            syncClientFromInput();
        });
        clientInput.addEventListener('blur', syncClientFromInput);
    }

    document.addEventListener('click', (e) => {
        if (clientDropdown.contains(e.target) || e.target === clientInput) return;
        hideClientDropdown();
    });

    employeeSelect?.addEventListener('change', () => {
        if (!selectedClientId) {
            setEmployeeDoc('');
            return;
        }
        const selectedOpt = employeeSelect.options[employeeSelect.selectedIndex];
        const docFromOpt = selectedOpt?.dataset?.document || '';
        if (docFromOpt) {
            setEmployeeDoc(docFromOpt);
            return;
        }
        const key = String(selectedClientId);
        const list = employeesByClient[key] || [];
        const match = list.find(emp => String(emp.id) === employeeSelect.value);
        setEmployeeDoc(match?.document || '');
    });

    const openCalendarModal = () => {
        if (!calendarModal) return;
        calendarModal.classList.remove('hidden');
        calendarModal.classList.add('flex');
    };

    const closeCalendarModal = () => {
        if (!calendarModal) return;
        calendarModal.classList.add('hidden');
        calendarModal.classList.remove('flex');
    };

    const pad2 = (n) => String(n).padStart(2, '0');

    // Calcula Pascua (algoritmo de Meeus/Jones/Butcher)
    const getEasterDate = (year) => {
        const a = year % 19;
        const b = Math.floor(year / 100);
        const c = year % 100;
        const d = Math.floor(b / 4);
        const e = b % 4;
        const f = Math.floor((b + 8) / 25);
        const g = Math.floor((b - f + 1) / 3);
        const h = (19 * a + b - d - g + 15) % 30;
        const i = Math.floor(c / 4);
        const k = c % 4;
        const l = (32 + 2 * e + 2 * i - h - k) % 7;
        const m = Math.floor((a + 11 * h + 22 * l) / 451);
        const month = Math.floor((h + l - 7 * m + 114) / 31);
        const day = ((h + l - 7 * m + 114) % 31) + 1;
        return new Date(year, month - 1, day);
    };

    const moveToMonday = (date) => {
        const d = new Date(date);
        const day = d.getDay();
        if (day === 0) d.setDate(d.getDate() + 1); // domingo -> lunes
        else if (day !== 1) d.setDate(d.getDate() + (8 - day)); // mover a lunes siguiente
        return d;
    };

    const getHolidays = (year) => {
        const easter = getEasterDate(year);
        const addDays = (date, days) => {
            const d = new Date(date);
            d.setDate(d.getDate() + days);
            return d;
        };

        const fixed = [
            new Date(year, 0, 1),   // Año Nuevo
            new Date(year, 4, 1),   // Día del Trabajo
            new Date(year, 6, 20),  // Independencia
            new Date(year, 7, 7),   // Batalla de Boyacá
            new Date(year, 11, 8),  // Inmaculada
            new Date(year, 11, 25), // Navidad
        ];

        const emiliani = [
            new Date(year, 0, 6),   // Reyes Magos
            new Date(year, 2, 19),  // San José
            new Date(year, 5, 29),  // San Pedro y San Pablo
            new Date(year, 7, 15),  // Asunción
            new Date(year, 9, 12),  // Día de la Raza
            new Date(year, 10, 1),  // Todos los Santos
            new Date(year, 10, 11), // Independencia de Cartagena
        ].map(moveToMonday);

        const easterRelative = [
            addDays(easter, -3),  // Jueves Santo
            addDays(easter, -2),  // Viernes Santo
            moveToMonday(addDays(easter, 39)), // Ascensión
            moveToMonday(addDays(easter, 60)), // Corpus Christi
            moveToMonday(addDays(easter, 67)), // Sagrado Corazón
        ];

        const all = [...fixed, ...emiliani, ...easterRelative];
        const set = new Set(all.map(d => `${d.getFullYear()}-${pad2(d.getMonth() + 1)}-${pad2(d.getDate())}`));
        return set;
    };

    const fillCalendar = (month, year) => {
        if (!dayNameCells.length || !dayNumberCells.length) return;
        const safeMonth = Number(month);
        const safeYear = Number(year);
        if (!safeMonth || !safeYear) return;

        const totalDays = new Date(safeYear, safeMonth, 0).getDate();
        const monthLabel = monthLabels[safeMonth] || '';
        const holidays = getHolidays(safeYear);

        dayNameCells.forEach((cell, idx) => {
            const dayNumber = idx + 1;
            if (dayNumber <= totalDays) {
                const date = new Date(safeYear, safeMonth - 1, dayNumber);
                const label = dayLabels[date.getDay()] || '';
                cell.textContent = label;
                const iso = `${safeYear}-${pad2(safeMonth)}-${pad2(dayNumber)}`;
                const isWeekend = date.getDay() === 0 || date.getDay() === 6;
                const isHoliday = holidays.has(iso);
                if (isWeekend || isHoliday) {
                    cell.classList.add('day-weekend');
                } else {
                    cell.classList.remove('day-weekend');
                }
            } else {
                cell.innerHTML = '&nbsp;';
                cell.classList.remove('day-weekend');
            }
        });

        dayNumberCells.forEach((cell, idx) => {
            const dayNumber = idx + 1;
            if (dayNumber <= totalDays) {
                const date = new Date(safeYear, safeMonth - 1, dayNumber);
                cell.textContent = dayNumber;
                const iso = `${safeYear}-${pad2(safeMonth)}-${pad2(dayNumber)}`;
                const isWeekend = date.getDay() === 0 || date.getDay() === 6;
                const isHoliday = holidays.has(iso);
                if (isWeekend || isHoliday) {
                    cell.classList.add('day-weekend');
                } else {
                    cell.classList.remove('day-weekend');
                }
            } else {
                cell.innerHTML = '&nbsp;';
                cell.classList.remove('day-weekend');
            }
        });

        if (monthTitleCell) {
            monthTitleCell.textContent = `${companyName} · Programación del mes ${monthLabel} del 1 al ${totalDays}`;
        }
    };

    btnCalendar?.addEventListener('click', (e) => {
        e.preventDefault();
        openCalendarModal();
    });

    btnCalendarClose?.addEventListener('click', (e) => {
        e.preventDefault();
        closeCalendarModal();
    });

    btnCalendarCancel?.addEventListener('click', (e) => {
        e.preventDefault();
        closeCalendarModal();
    });

    calendarModal?.addEventListener('click', (e) => {
        if (e.target === calendarModal) closeCalendarModal();
    });

    btnCalendarSave?.addEventListener('click', (e) => {
        e.preventDefault();
        const month = calendarMonth?.value || '';
        const year = calendarYear?.value || '';
        fillCalendar(month, year);
        closeCalendarModal();
    });

    renderEmployeeOptions(null);
});
</script>
@endsection

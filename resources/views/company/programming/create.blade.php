@extends('layouts.company')

@section('content')
@include('company.programming.partials.header')

@php
    $company = $company ?? auth()->user()->company;
    $clients = $clients ?? collect();
    $turns = $turns ?? collect();
    $turnData = $turns->map(function ($t) {
        return [
            'id' => $t->id,
            'name' => $t->name,
            'description' => $t->description,
            'color' => $t->color,
        ];
    });
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
        <table class="min-w-full text-sm text-gray-700 border border-black overflow-hidden" style="table-layout: fixed;">
            <colgroup>
                @for ($c = 1; $c <= 33; $c++)
                    @if ($c === 1)
                        <col style="width:6%;">
                    @elseif ($c === 2)
                        <col style="width:9%;">
                    @else
                        <col style="width:2.74%;">
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
                    {{-- Col 3-33 combinado con campo de clientes --}}
                    <td class="px-2 py-1 border border-black bg-white" colspan="31">
                        <input id="client-input" class="client-input w-full h-full text-center" autocomplete="off" placeholder="Seleccione cliente" />
                    </td>
                </tr>
                {{-- Fila 2 (col 3-33 combinadas) --}}
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
                {{-- Fila 5 (turnos en col 3-33, editable) --}}
                <tr class="bg-gray-50 border-b border-gray-200">
                    @for ($c = 1; $c <= 33; $c++)
                        @if ($c <= 2)
                            <td class="px-2 py-1 border border-black text-transparent bg-white">&nbsp;</td>
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
`;
document.head.appendChild(turnInputStyle);

// Control de turnos: crear/editar/eliminar y pintar celdas
document.addEventListener('DOMContentLoaded', () => {
    const turns = @json($turnData);
    const clients = @json($clients->map(fn($c) => ['id' => $c->id, 'name' => $c->business_name]));

    const modal = document.getElementById('turno-modal');
    const title = document.getElementById('turno-form-title');
    const btnCreate = document.getElementById('btn-turno-create');
    const btnEdit = document.getElementById('btn-turno-edit');
    const btnDelete = document.getElementById('btn-turno-delete');
    const btnClose = document.getElementById('btn-turno-close');
    const btnCancel = document.getElementById('btn-turno-cancel');
    const turnInputs = document.querySelectorAll('.turn-input');

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
    turnInputs.forEach((input) => {
        // Fuerza mayúsculas al escribir
        input.addEventListener('input', () => {
            input.value = (input.value || '').toUpperCase();
        });

        input.addEventListener('focus', () => {
            showDropdown(input);
        });

        input.addEventListener('click', () => {
            showDropdown(input);
        });

        input.addEventListener('change', () => {
            const value = (input.value || '').trim().toUpperCase();
            const cell = input.closest('td');
            const match = turns.find(t => t.name.toUpperCase() === value);
            if (match) {
                if (cell) cell.style.backgroundColor = match.color || '#ffffff';
            } else {
                if (cell) cell.style.backgroundColor = '#ffffff';
                if (value !== '') {
                    alert('Este valor no existe, selecciona uno que exista o créalo.');
                    input.value = '';
                }
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
        const filtered = clients.filter(c => c.name.toLowerCase().includes(term));
        clientDropdown.innerHTML = filtered.map(c => `
            <button type="button" data-id="${c.id}" data-name="${c.name}">${c.name}</button>
        `).join('') || '<div class="px-3 py-2 text-sm text-gray-500">Sin coincidencias</div>';
        const rect = input.getBoundingClientRect();
        clientDropdown.style.left = `${rect.left + window.scrollX}px`;
        clientDropdown.style.top = `${rect.bottom + window.scrollY + 4}px`;
        clientDropdown.style.minWidth = `${rect.width}px`;
        clientDropdown.classList.remove('hidden');
    };

    clientDropdown.addEventListener('mousedown', (e) => {
        const btn = e.target.closest('button[data-name]');
        if (!btn) return;
        e.preventDefault();
        const name = btn.dataset.name;
        clientInput.value = name;
        hideClientDropdown();
    });

    if (clientInput) {
        clientInput.addEventListener('focus', () => showClientDropdown(clientInput));
        clientInput.addEventListener('click', () => showClientDropdown(clientInput));
        clientInput.addEventListener('input', () => showClientDropdown(clientInput));
    }

    document.addEventListener('click', (e) => {
        if (clientDropdown.contains(e.target) || e.target === clientInput) return;
        hideClientDropdown();
    });
});
</script>
@endsection

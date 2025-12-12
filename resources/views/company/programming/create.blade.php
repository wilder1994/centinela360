@extends('layouts.company')

@php
    use Carbon\Carbon;
    use App\Models\Client;

    $today = now()->locale('es');
    $firstDay = $today->copy()->startOfMonth();
    $days = collect(range(1, 31))->map(fn ($day) => $firstDay->copy()->addDays($day - 1));

    $turnOptionsHtml = '<option value="">-</option>';
    $turnOptionsHtml .= '<option value="Z">Z</option>';
    $turnOptionsHtml .= '<option value="D">D</option>';
    $turnOptionsHtml .= '<option value="N">N</option>';
    $turnOptionsHtml .= '<option value="R">R</option>';

    $clients = Client::where('company_id', auth()->user()->company_id ?? null)
        ->orderBy('business_name')
        ->get(['id', 'business_name']);
@endphp

@section('content')
<style>
    :root {
        --col-id: 12rem;
        --col-name: 14rem;
        --col-day: calc((100% - (var(--col-id) + var(--col-name))) / 31);
    }
    .turno-table { width: 100%; table-layout: fixed; border-collapse: collapse; }
    .turno-table th, .turno-table td {
        border: 1px solid var(--border);
        padding: 0.3rem;
        vertical-align: middle;
        height: 38px;
    }
    .cell-id { width: var(--col-id); min-width: var(--col-id); }
    .cell-name { width: var(--col-name); min-width: var(--col-name); }
    .turno-cell { padding: 0 !important; }
    .turno-input-cell {
        width: 100%;
        height: 100%;
        border: none;
        background: transparent;
        color: #e2e8f0;
        text-align: center;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        line-height: 36px;
        caret-color: #e2e8f0;
        -webkit-text-fill-color: currentColor;
        padding: 0;
        appearance: none;
        -webkit-appearance: none;
    }
    .turno-input-cell::-webkit-calendar-picker-indicator { display: none !important; }
    .turno-input-cell:focus { outline: none; box-shadow: inset 0 0 0 1px var(--primary); }
    .turno-input {
        width: 100%; height: 100%;
        padding: 0.35rem 0.4rem;
        background: transparent;
        border: none;
        color: #e2e8f0;
    }
    .client-select {
        width: 100%;
        height: 100%;
        border: none;
        background: transparent;
        color: #e2e8f0;
        text-align: center;
        font-weight: 600;
        padding: 0.35rem 0.5rem;
    }
    .client-select:focus {
        outline: none;
        box-shadow: inset 0 0 0 1px var(--primary);
    }
    .employee-select {
        width: 100%;
        height: 100%;
        border: none;
        background: transparent;
        color: #e2e8f0;
        text-align: left;
        padding: 0.35rem 0.5rem;
    }
    .employee-select:focus {
        outline: none;
        box-shadow: inset 0 0 0 1px var(--primary);
    }
</style>
<div class="text-white space-y-6" style="
    --primary: var(--primary-color, #22d3ee);
    --secondary: var(--secondary-color, #0f1827);
    --panel: color-mix(in srgb, var(--secondary) 85%, #000 15%);
    --card: color-mix(in srgb, var(--secondary) 78%, #000 22%);
    --border: rgba(255,255,255,0.08);
">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-gray-400">Programaci&oacute;n</p>
            <h1 class="text-3xl font-semibold text-gray-900">Crear malla operativa</h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('company.programming.index') }}" class="px-4 py-2 border border-gray-700 text-gray-200 rounded-md hover:bg-gray-800 transition">Volver</a>
            <button class="px-4 py-2 rounded-md shadow-sm opacity-60 cursor-not-allowed" style="background: var(--primary); color: #0b1220;">Guardar (pr&oacute;ximamente)</button>
        </div>
    </div>

    <div class="rounded-xl shadow-lg p-5 space-y-4" style="background: var(--panel); border: 1px solid var(--border);">
        <div class="flex justify-center gap-3 pb-4">
            <button class="px-4 py-2 text-xs font-semibold rounded-md border border-slate-700 hover:bg-slate-800 transition manage-turno" data-action="create">Crear item</button>
            <button class="px-4 py-2 text-xs font-semibold rounded-md border border-slate-700 hover:bg-slate-800 transition manage-turno" data-action="edit">Editar item</button>
            <button class="px-4 py-2 text-xs font-semibold rounded-md border border-slate-700 hover:bg-slate-800 transition manage-turno" data-action="delete">Eliminar item</button>
        </div>

        <div id="turno-cards" class="grid gap-3 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5"></div>
    </div>

    <div class="rounded-xl shadow-lg p-5 overflow-hidden" style="background: var(--panel); border: 1px solid var(--border);">
        <div class="overflow-hidden w-full" style="background: var(--card); border: 1px solid var(--border); border-top: 0;">
            <table class="turno-table text-xs text-gray-200">
                <colgroup>
                    <col style="width: var(--col-id);">
                    <col style="width: var(--col-name);">
                    @for ($i = 0; $i < 31; $i++)
                        <col style="width: var(--col-day);">
                    @endfor
                </colgroup>
                <thead>
                    <tr class="text-gray-300 head-cell" style="background: var(--panel);">
                        <th class="head-cell text-center" rowspan="3">
                            <div class="h-12 w-12 rounded-full flex items-center justify-center text-sm text-gray-400" style="background: var(--panel); border: 1px solid var(--border);">Logo</div>
                        </th>
                        <th class="head-cell text-center" rowspan="3">
                            <div class="flex flex-col items-center gap-3">
                                <div class="flex items-center gap-2">
                                    <button class="h-9 w-9 rounded-md border border-slate-700 text-sm font-semibold text-gray-200 hover:bg-slate-800 transition" aria-label="Agregar fila">+</button>
                                    <button class="h-9 w-9 rounded-md border border-rose-600/60 text-sm font-semibold text-rose-200 hover:bg-rose-700/20 transition" aria-label="Eliminar fila">-</button>
                                </div>
                                <button class="px-3 py-1.5 rounded-md border border-slate-700 text-xs font-semibold text-gray-200 hover:bg-slate-800 transition" aria-label="Historial">Historial</button>
                            </div>
                        </th>
                        <th class="head-cell text-center" colspan="31">
                            <div class="w-full h-full">
                                <input id="client-search" list="client-options" autocomplete="off"
                                       class="client-select"
                                       placeholder="Seleccione un cliente o escriba para buscar">
                                <input type="hidden" id="client-selected-id">
                                <datalist id="client-options">
                                    @foreach ($clients as $client)
                                        <option data-id="{{ $client->id }}" value="{{ $client->business_name }}"></option>
                                    @endforeach
                                </datalist>
                            </div>
                        </th>
                    </tr>
                    <tr class="text-gray-300 head-cell" style="background: var(--panel);">
                        <th class="head-cell text-center" colspan="31">P3 SEGURIDAD LTDA - SELECCIONA EL MES Y EL A&Ntilde;O</th>
                    </tr>
                    <tr class="text-gray-300" style="background: var(--panel);">
                        @foreach ($days as $day)
                            <th class="head-cell text-center">{{ $day->locale('es')->isoFormat('dd') }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-slate-900/60 text-gray-400">
                        <td class="cell-id text-left whitespace-nowrap">C&eacute;dula</td>
                        <td class="cell-name text-left whitespace-nowrap">Nombre</td>
                        @foreach ($days as $day)
                            <td class="head-cell text-center text-[11px]">{{ $day->day }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="cell-id text-sm text-white whitespace-nowrap"><span id="employee-document">-</span></td>
                        <td class="cell-name text-sm text-white whitespace-nowrap">
                            <select id="employee-select" class="employee-select">
                                <option value="">Seleccione el cliente primero</option>
                            </select>
                        </td>
                        @foreach ($days as $day)
                            <td class="turno-cell">
                                <input class="turno-input-cell day-turn" maxlength="2">
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<datalist id="turno-codes-list"></datalist>

@include('company.programming.turno-modal')

<script>
document.addEventListener('DOMContentLoaded', () => {
    const cardsContainer = document.getElementById('turno-cards');
    const manageButtons = document.querySelectorAll('.manage-turno');
    const clientInput = document.getElementById('client-search');
    const clientDatalist = document.getElementById('client-options');
    const clientHidden = document.getElementById('client-selected-id');
    const employeeSelect = document.getElementById('employee-select');
    const employeeDocument = document.getElementById('employee-document');
    const dayTurnInputs = Array.from(document.querySelectorAll('.day-turn'));
    const turnoCodesList = document.getElementById('turno-codes-list');
    let currentEmployees = [];

    const rowsPerCard = 4;
    const maxCards = 5;
    const maxTurnos = rowsPerCard * maxCards;
    let turnos = [];

    const modal = document.getElementById('turno-modal');
    const modalSelectWrapper = document.getElementById('turno-select-wrapper');
    const modalSelect = document.getElementById('turno-modal-select');
    const modalCode = document.getElementById('turno-modal-code');
    const modalDesc = document.getElementById('turno-modal-desc');
    const modalColor = document.getElementById('turno-modal-color');
    const modalHeading = document.getElementById('turno-modal-heading');
    const modalSubmit = document.getElementById('turno-modal-submit');
    const modalClose = document.getElementById('turno-modal-close');
    const modalCancel = document.getElementById('turno-modal-cancel');
    const colorPills = Array.from(document.querySelectorAll('.turno-color-pill'));
    let modalMode = 'create';

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    const fetchJson = async (url, options = {}) => {
        const res = await fetch(url, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
            ...options,
        });
        const text = await res.text();
        const looksLikeHtml = text && text.trim().startsWith('<');
        const parseOrFallback = () => {
            if (!text) return {};
            const clean = text.replace(/^\uFEFF/, '');
            try {
                return JSON.parse(clean);
            } catch (err) {
                return null;
            }
        };
        const parsed = parseOrFallback();
        if (!res.ok) {
            const message = parsed?.message || parsed?.error || text || res.statusText;
            if (res.status === 419) {
                alert('Tu sesión o token CSRF expiró. Refresca la página e inténtalo de nuevo.');
                throw new Error(message);
            }
            throw new Error(message);
        }
        if (looksLikeHtml) {
            const snippet = text ? text.slice(0, 200) : '';
            throw new Error('Respuesta inesperada del servidor: ' + snippet);
        }
        if (parsed === null) {
            // devolver texto crudo si no se pudo parsear pero no es HTML
            return text;
        }
        return parsed;
    };

    const debounce = (fn, delay = 300) => {
        let t;
        return (...args) => {
            clearTimeout(t);
            t = setTimeout(() => fn(...args), delay);
        };
    };

    const fillClientOptions = (items) => {
        if (!clientDatalist) return;
        clientDatalist.innerHTML = '';
        items.forEach((c) => {
            const opt = document.createElement('option');
            opt.value = c.business_name;
            opt.dataset.id = c.id;
            clientDatalist.appendChild(opt);
        });
    };

    const handleClientInput = async (value) => {
        try {
            const data = await fetchJson(`{{ route('company.clients.search') }}?q=${encodeURIComponent(value)}`);
            if (Array.isArray(data)) {
                fillClientOptions(data);
            }
        } catch (e) {
            console.error('Error buscando clientes', e);
        }
    };

    const syncClientSelection = () => {
        if (!clientInput || !clientHidden || !clientDatalist) return;
        const match = Array.from(clientDatalist.options).find((opt) => opt.value === clientInput.value);
        clientHidden.value = match ? match.dataset.id || '' : '';
    };

    const fillEmployeeOptions = (items) => {
        currentEmployees = items;
        if (!employeeSelect) return;
        employeeSelect.innerHTML = '';
        if (!items.length) {
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = clientHidden?.value ? 'No hay empleados para el cliente' : 'Seleccione el cliente primero';
            employeeSelect.appendChild(opt);
            employeeSelect.disabled = true;
            if (employeeDocument) employeeDocument.textContent = '-';
            return;
        }
        employeeSelect.disabled = false;
        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Seleccione empleado';
        employeeSelect.appendChild(placeholder);
        items.forEach((emp) => {
            const opt = document.createElement('option');
            opt.value = emp.id;
            opt.textContent = `${emp.first_name ?? ''} ${emp.last_name ?? ''}`.trim() || emp.id;
            employeeSelect.appendChild(opt);
        });
        if (employeeDocument) employeeDocument.textContent = '-';
    };

    const loadEmployeesForClient = async () => {
        if (!clientHidden || !clientHidden.value) {
            fillEmployeeOptions([]);
            return;
        }
        try {
            const data = await fetchJson(`{{ route('company.employees.by_client') }}?client_id=${clientHidden.value}`);
            if (Array.isArray(data)) {
                fillEmployeeOptions(data);
            } else {
                fillEmployeeOptions([]);
            }
        } catch (e) {
            console.error('Error cargando empleados', e);
            fillEmployeeOptions([]);
        }
    };

    const loadTurnos = async () => {
        try {
            const data = await fetchJson('{{ route('company.turnos.index') }}');
            if (Array.isArray(data)) {
                turnos = data;
            } else if (data && Array.isArray(data.data)) {
                // por si el backend envía formato {data: [...]}
                turnos = data.data;
            } else if (typeof data === 'string') {
                try {
                    const parsed = JSON.parse(data);
                    if (Array.isArray(parsed)) {
                        turnos = parsed;
                    } else if (parsed && Array.isArray(parsed.data)) {
                        turnos = parsed.data;
                    } else {
                        turnos = [];
                    }
                } catch (err) {
                    console.warn('Respuesta de turnos no parseable:', data);
                    turnos = [];
                }
            } else {
                console.warn('Respuesta inesperada para turnos:', data);
                turnos = [];
            }
            refreshTurnoCodesDatalist();
            renderCards();
        } catch (e) {
            console.error('Error cargando turnos', e);
            alert(e.message || 'No se pudieron cargar los turnos');
        }
    };

    const renderCards = () => {
        cardsContainer.innerHTML = '';
        if (!Array.isArray(turnos) || !turnos.length) return;
        const cardsNeeded = Math.min(maxCards, Math.ceil(turnos.length / rowsPerCard));
        for (let c = 0; c < cardsNeeded; c++) {
            const slice = turnos.slice(c * rowsPerCard, (c + 1) * rowsPerCard);
            const rows = Array.from({ length: rowsPerCard }).map((_, idx) => {
                const t = slice[idx];
                const color = (t && t.color) ? t.color : 'transparent';
                return `
                    <tr class="border-b border-slate-800/60 last:border-b-0">
                        <td class="px-3 py-2" style="${t ? `background:${color}; color:#0b1220;` : ''}">
                            ${t ? `<span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full" style="background:${color};"></span><span class="font-semibold">${t.code}</span></span>` : '<span class="text-slate-500">-</span>'}
                        </td>
                        <td class="px-3 py-2 font-semibold" style="${t ? `background:${color}; color:#0b1220;` : ''}">${t ? t.description : 'Sin definir'}</td>
                    </tr>
                `;
            }).join('');

            const card = document.createElement('div');
            card.className = 'rounded-lg border border-slate-800/70 overflow-hidden';
            card.style.background = 'var(--card)';
            card.innerHTML = `
                <table class="w-full text-xs text-gray-200 border-collapse">
                    <thead class="bg-slate-900/60 text-gray-300">
                        <tr>
                            <th class="px-3 py-2 border-b border-slate-800/70 text-left">Item de turno</th>
                            <th class="px-3 py-2 border-b border-slate-800/70 text-left">Descripci&oacute;n</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            `;
            cardsContainer.appendChild(card);
        }
    };

    const refreshTurnoCodesDatalist = () => {
        if (!turnoCodesList) return;
        turnoCodesList.innerHTML = '';
        turnos.forEach((t) => {
            const opt = document.createElement('option');
            opt.value = t.code;
            opt.dataset.color = t.color;
            turnoCodesList.appendChild(opt);
        });
    };

    const setColor = (value) => {
        if (!modalColor) return;
        modalColor.value = value;
        colorPills.forEach((pill) => {
            if (pill.dataset.color === value) {
                pill.classList.add('ring-2', 'ring-primary', 'ring-offset-2', 'ring-offset-slate-900');
            } else {
                pill.classList.remove('ring-2', 'ring-primary', 'ring-offset-2', 'ring-offset-slate-900');
            }
        });
    };

    colorPills.forEach((pill) => pill.addEventListener('click', () => setColor(pill.dataset.color)));

    const openModal = (mode) => {
        modalMode = mode;
        modalCode.value = '';
        modalDesc.value = '';
        setColor('#22d3ee');
        modalSelect.innerHTML = '';
        const needsSelect = mode !== 'create';
        modalSelectWrapper.classList.toggle('hidden', !needsSelect);
        if (needsSelect) {
            turnos.forEach((t, idx) => {
                const opt = document.createElement('option');
                opt.value = t.id;
                opt.textContent = `${idx + 1}. ${t.code} - ${t.description}`;
                modalSelect.appendChild(opt);
            });
            modalSelect.disabled = !turnos.length;
            if (turnos.length) {
                modalSelect.value = turnos[0].id;
                modalCode.value = turnos[0].code;
                modalDesc.value = turnos[0].description;
                setColor(turnos[0].color || '#22d3ee');
            }
        }
        modalHeading.textContent = mode === 'create' ? 'Crear turno' : mode === 'edit' ? 'Editar turno' : 'Eliminar turno';
        modalSubmit.textContent = mode === 'delete' ? 'Eliminar' : 'Guardar';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    modalSelect?.addEventListener('change', () => {
        if (!turnos.length) return;
        const selected = turnos.find((t) => String(t.id) === String(modalSelect.value));
        if (selected) {
            modalCode.value = selected.code;
            modalDesc.value = selected.description;
            setColor(selected.color || '#22d3ee');
        }
    });

    const closeModal = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    const handleSubmit = async () => {
        try {
            if (modalMode === 'create') {
                if (turnos.length >= maxTurnos) {
                    alert('sin espacio para crear turnos, edita una o elimina uno');
                    return;
                }
                const code = modalCode.value.trim().toUpperCase();
                const desc = modalDesc.value.trim();
                const color = modalColor.value || '#22d3ee';
                if (!code || !desc) return;
                await fetchJson('{{ route('company.turnos.store') }}', {
                    method: 'POST',
                    body: JSON.stringify({ code, description: desc, color, _token: csrf }),
                });
            } else if (modalMode === 'edit') {
                const id = modalSelect.value;
                if (!id) return;
                const code = modalCode.value.trim().toUpperCase();
                const desc = modalDesc.value.trim();
                const color = modalColor.value || '#22d3ee';
                if (!code || !desc) return;
                await fetchJson(`{{ url('company/turnos') }}/${id}`, {
                    method: 'PUT',
                    body: JSON.stringify({ code, description: desc, color, _token: csrf }),
                });
            } else if (modalMode === 'delete') {
                const id = modalSelect.value;
                if (!id) return;
                await fetchJson(`{{ url('company/turnos') }}/${id}`, { method: 'DELETE', body: JSON.stringify({ _token: csrf }) });
            }
            await loadTurnos();
        } catch (e) {
            alert(e.message || 'Error');
        }
    };

    manageButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            const action = btn.dataset.action;
            if (action === 'create') {
                openModal('create');
            } else if (action === 'edit') {
                if (!turnos.length) { alert('No hay turnos para editar'); return; }
                openModal('edit');
            } else if (action === 'delete') {
                if (!turnos.length) { alert('No hay turnos para eliminar'); return; }
                openModal('delete');
            }
        });
    });

    modalSubmit.addEventListener('click', (e) => { e.preventDefault(); handleSubmit(); });
    modalClose.addEventListener('click', (e) => { e.preventDefault(); closeModal(); });
    modalCancel.addEventListener('click', (e) => { e.preventDefault(); closeModal(); });
    modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

    fillEmployeeOptions([]);
    if (clientInput) {
        clientInput.addEventListener('input', debounce(() => handleClientInput(clientInput.value), 250));
        clientInput.addEventListener('change', syncClientSelection);
        clientInput.addEventListener('blur', () => { syncClientSelection(); loadEmployeesForClient(); });
    }

    employeeSelect?.addEventListener('change', () => {
        if (!employeeDocument) return;
        const found = currentEmployees.find((emp) => String(emp.id) === String(employeeSelect.value));
        employeeDocument.textContent = found?.document_number || '-';
    });

    const getContrastColor = (hex) => {
        if (!hex || typeof hex !== 'string') return '#0b1220';
        const raw = hex.replace('#', '');
        if (![3,6].includes(raw.length)) return '#0b1220';
        const full = raw.length === 3 ? raw.split('').map((c) => c + c).join('') : raw;
        const r = parseInt(full.substring(0,2), 16);
        const g = parseInt(full.substring(2,4), 16);
        const b = parseInt(full.substring(4,6), 16);
        const luminance = 0.299*r + 0.587*g + 0.114*b;
        return luminance > 150 ? '#0b1220' : '#f8fafc';
    };

    const applyTurnColor = (input, code) => {
        const found = turnos.find((t) => t.code.toUpperCase() === code.toUpperCase());
        if (found) {
            const upper = found.code.toUpperCase();
            input.value = upper;
            const bg = found.color || 'transparent';
            const fg = getContrastColor(found.color);
            input.style.background = bg;
            input.style.setProperty('color', fg, 'important');
            input.style.setProperty('-webkit-text-fill-color', fg, 'important');
            input.style.setProperty('font-weight', '700', 'important');
            input.style.setProperty('text-transform', 'uppercase', 'important');
        } else {
            input.value = code;
            input.style.background = 'transparent';
            input.style.setProperty('color', '#e2e8f0', 'important');
            input.style.setProperty('-webkit-text-fill-color', '#e2e8f0', 'important');
            input.style.removeProperty('font-weight');
        }
    };

    const handleTurnInput = (input) => {
        const raw = (input.value || '').trim();
        const value = raw.toUpperCase();
        if (!value) {
            applyTurnColor(input, '');
            return;
        }
        const found = turnos.find((t) => t.code.toUpperCase() === value);
        if (!found) {
            alert('este valor no es valido');
            input.value = '';
            applyTurnColor(input, '');
            return;
        }
        applyTurnColor(input, value);
    };

    const showTurnPicker = (inp) => {
        // Forzar apertura de datalist
        if (typeof inp.showPicker === 'function') {
            try { inp.showPicker(); return; } catch (e) {}
        }
        setTimeout(() => {
            const ev = new KeyboardEvent('keydown', { key: 'ArrowDown', bubbles: true });
            inp.dispatchEvent(ev);
        }, 10);
    };

    dayTurnInputs.forEach((inp) => {
        inp.setAttribute('list', 'turno-codes-list');
        inp.addEventListener('change', () => handleTurnInput(inp));
        inp.addEventListener('blur', () => {
            // si no eligió nada, restaurar el valor anterior
            if (!inp.value && inp.dataset.prevTurnCode) {
                inp.value = inp.dataset.prevTurnCode;
                handleTurnInput(inp);
            } else {
                handleTurnInput(inp);
            }
            inp.dataset.prevTurnCode = '';
        });
        inp.addEventListener('focus', () => {
            const current = (inp.value || '').trim();
            inp.dataset.prevTurnCode = current;
            // limpiar para que el datalist muestre todas las opciones
            inp.value = '';
            setTimeout(() => showTurnPicker(inp), 0);
        });
        inp.addEventListener('input', () => applyTurnColor(inp, (inp.value || '').trim().toUpperCase()));
    });

    loadTurnos();
});
</script>
@endsection

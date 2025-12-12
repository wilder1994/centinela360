@extends('layouts.company')

@php
    use Carbon\Carbon;

    $today = now()->locale('es');
    $firstDay = $today->copy()->startOfMonth();
    $days = collect(range(1, 31))->map(fn ($day) => $firstDay->copy()->addDays($day - 1));

    $items = [
        ['label' => 'Descanso', 'code' => 'Z'],
        ['label' => 'Diurno', 'code' => 'D'],
        ['label' => 'Nocturno', 'code' => 'N'],
        ['label' => 'Reserva', 'code' => 'R'],
    ];

    $turnOptionsHtml = '<option value="">-</option>';
    foreach ($items as $item) {
        $turnOptionsHtml .= "<option value=\"{$item['code']}\">{$item['code']}</option>";
    }
@endphp

@section('content')
<style>
    :root {
        --col-id: 12rem;
        --col-name: 14rem;
        --col-day: calc((100% - (var(--col-id) + var(--col-name))) / 31);
    }
    .turno-table { width: 100%; table-layout: fixed; border-collapse: collapse; }
    .turno-table th, .turno-table td { border: 1px solid var(--border); padding: 0.3rem; vertical-align: middle; }
    .cell-id { width: var(--col-id); min-width: var(--col-id); }
    .cell-name { width: var(--col-name); min-width: var(--col-name); }
    .turno-cell { padding: 0 !important; }
    .turno-select {
        width: 100%;
        height: 100%;
        padding: 0;
        border: 0;
        background: transparent;
        color: #e2e8f0;
        text-align: center;
        font-size: 11px;
    }
    .turno-select:focus { outline: 1px solid var(--primary); }
    .turno-input {
        width: 100%;
        height: 100%;
        padding: 0.35rem 0.4rem;
        background: transparent;
        border: none;
        color: #e2e8f0;
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
            <p class="text-xs uppercase tracking-[0.2em] text-gray-400">ProgramaciÃ³n</p>
            <h1 class="text-3xl font-semibold text-gray-900">Crear malla operativa</h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('company.programming.index') }}" class="px-4 py-2 border border-gray-700 text-gray-200 rounded-md hover:bg-gray-800 transition">Volver</a>
            <button class="px-4 py-2 rounded-md shadow-sm opacity-60 cursor-not-allowed" style="background: var(--primary); color: #0b1220;">Guardar (prÃ³ximamente)</button>
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
                        <th class="head-cell text-center" colspan="31">CONDOMINIO HONTANER DE LAS MERCEDEZ - PORTERÃA SERVICIO 24 HORAS</th>
                    </tr>
                    <tr class="text-gray-300 head-cell" style="background: var(--panel);">
                        <th class="head-cell text-center" colspan="31">P3 SEGURIDAD LTDA - SELECIONA EL MES Y EL AÃ‘O</th>
                    </tr>
                    <tr class="text-gray-300" style="background: var(--panel);">
                        @foreach ($days as $day)
                            <th class="head-cell text-center">{{ $day->locale('es')->isoFormat('dd') }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-slate-900/60 text-gray-400">
                        <td class="cell-id text-left whitespace-nowrap">CÃ©dula</td>
                        <td class="cell-name text-left whitespace-nowrap">Nombre</td>
                        @foreach ($days as $day)
                            <td class="head-cell text-center text-[11px]">{{ $day->day }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="cell-id text-sm text-white whitespace-nowrap">10.234.567</td>
                        <td class="cell-name text-sm text-white whitespace-nowrap">Nombre del guarda</td>
                        @foreach ($days as $day)
                            <td class="turno-cell">
                                <select class="turno-select">
                                    {!! $turnOptionsHtml !!}
                                </select>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

<div id="turno-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-slate-900 border border-slate-800 rounded-xl shadow-2xl w-full max-w-sm mx-4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-800">
            <div>
                <p id="turno-modal-title" class="text-xs uppercase text-gray-400">Gestionar turno</p>
                <h3 id="turno-modal-heading" class="text-lg font-semibold text-white">Crear turno</h3>
            </div>
            <button id="turno-modal-close" class="text-gray-400 hover:text-white text-xl px-2" aria-label="Cerrar">&times;</button>
        </div>
        <form id="turno-modal-form" class="px-5 py-4 space-y-3 text-sm text-gray-200">
            <div id="turno-select-wrapper" class="space-y-1 hidden">
                <label class="text-gray-300">Selecciona un turno</label>
                <select id="turno-modal-select" class="w-full rounded-md bg-slate-800 border border-slate-700 px-3 py-2 focus:ring-1 focus:ring-primary focus:border-primary"></select>
            </div>
            <div class="space-y-1">
                <label class="text-gray-300">Código</label>
                <input id="turno-modal-code" type="text" maxlength="2" class="w-full rounded-md bg-slate-800 border border-slate-700 px-3 py-2 focus:ring-1 focus:ring-primary focus:border-primary" placeholder="Ej: D, N, R">
            </div>
            <div class="space-y-1">
                <label class="text-gray-300">Descripción</label>
                <input id="turno-modal-desc" type="text" class="w-full rounded-md bg-slate-800 border border-slate-700 px-3 py-2 focus:ring-1 focus:ring-primary focus:border-primary" placeholder="Ej: 08:00 a 17:00">
            </div>
            <div class="space-y-1">
                <label class="text-gray-300">Color</label>
                <input id="turno-modal-color" type="color" class="h-10 w-full rounded-md border border-slate-700 bg-slate-800 p-1" value="#22d3ee">
            </div>
        </form>
        <div class="px-5 py-4 border-t border-slate-800 flex justify-end gap-2">
            <button id="turno-modal-cancel" class="px-4 py-2 rounded-md border border-slate-700 text-gray-200 hover:bg-slate-800 transition">Cancelar</button>
            <button id="turno-modal-submit" class="px-4 py-2 rounded-md bg-primary text-white hover:brightness-110 transition">Guardar</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const cardsContainer = document.getElementById('turno-cards');
    const manageButtons = document.querySelectorAll('.manage-turno');

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
    let modalMode = 'create';

    const renderCards = () => {
        cardsContainer.innerHTML = '';
        if (!turnos.length) return;
        const cardsNeeded = Math.min(maxCards, Math.ceil(turnos.length / rowsPerCard));
        for (let c = 0; c < cardsNeeded; c++) {
            const slice = turnos.slice(c * rowsPerCard, (c + 1) * rowsPerCard);
            const rows = Array.from({ length: rowsPerCard }).map((_, idx) => {
                const t = slice[idx];
                return `
                    <tr class="border-b border-slate-800/60 last:border-b-0">
                        <td class="px-3 py-2" style="${t ? `background:${t.color}; color:#0b1220;` : ''}">
                            ${t ? `<span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full" style="background:${t.color};"></span><span class="font-semibold">${t.code}</span></span>` : '<span class="text-slate-500">-</span>'}
                        </td>
                        <td class="px-3 py-2 font-semibold" style="${t ? `background:${t.color}; color:#0b1220;` : ''}">${t ? t.desc : 'Sin definir'}</td>
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
                            <th class="px-3 py-2 border-b border-slate-800/70 text-left">Descripción</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            `;
            cardsContainer.appendChild(card);
        }
    };

    const openModal = (mode) => {
        modalMode = mode;
        modalCode.value = '';
        modalDesc.value = '';
        modalColor.value = '#22d3ee';
        modalSelect.innerHTML = '';
        const needsSelect = mode !== 'create';
        modalSelectWrapper.classList.toggle('hidden', !needsSelect);
        if (needsSelect) {
            turnos.forEach((t, idx) => {
                const opt = document.createElement('option');
                opt.value = idx;
                opt.textContent = `${idx + 1}. ${t.code} - ${t.desc}`;
                modalSelect.appendChild(opt);
            });
            modalSelect.disabled = !turnos.length;
            if (turnos.length) {
                modalSelect.value = 0;
                modalCode.value = turnos[0].code;
                modalDesc.value = turnos[0].desc;
                modalColor.value = turnos[0].color || '#22d3ee';
            }
        }
        modalHeading.textContent = mode === 'create' ? 'Crear turno' : mode === 'edit' ? 'Editar turno' : 'Eliminar turno';
        modalSubmit.textContent = mode === 'delete' ? 'Eliminar' : 'Guardar';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    const closeModal = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    const handleSubmit = () => {
        if (modalMode === 'create') {
            if (turnos.length >= maxTurnos) {
                alert('sin espacio para crear turnos, edita una o elimina uno');
                return;
            }
            const code = modalCode.value.trim().toUpperCase();
            const desc = modalDesc.value.trim();
            const color = modalColor.value || '#22d3ee';
            if (!code || !desc) return;
            turnos.push({ code, desc, color });
        } else if (modalMode === 'edit') {
            const idx = parseInt(modalSelect.value, 10);
            if (Number.isNaN(idx) || !turnos[idx]) return;
            const code = modalCode.value.trim().toUpperCase();
            const desc = modalDesc.value.trim();
            const color = modalColor.value || '#22d3ee';
            if (!code || !desc) return;
            turnos[idx] = { code, desc, color };
        } else if (modalMode === 'delete') {
            const idx = parseInt(modalSelect.value, 10);
            if (Number.isNaN(idx) || !turnos[idx]) return;
            turnos.splice(idx, 1);
        }
        renderCards();
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

    renderCards();
});
</script>

@extends('layouts.company')

@php
    use Carbon\Carbon;

    $today = now()->locale('es');
    $firstDay = $today->copy()->startOfMonth();
    $daysInMonth = $today->daysInMonth;
    $days = collect(range(1, $daysInMonth))->map(fn ($day) => $firstDay->copy()->addDays($day - 1));

    $items = [
        ['label' => 'Descanso', 'code' => 'Z', 'color' => 'bg-amber-500 text-black', 'turns' => ['12 h. 06:00 a 18:00', '12 h. 18:00 a 06:00']],
        ['label' => 'Diurno', 'code' => 'D', 'color' => 'bg-sky-500 text-black', 'turns' => ['08:00 a 17:00']],
        ['label' => 'Nocturno', 'code' => 'N', 'color' => 'bg-indigo-500 text-white', 'turns' => ['20:00 a 06:00']],
        ['label' => 'Reserva', 'code' => 'R', 'color' => 'bg-emerald-500 text-black', 'turns' => ['Disponible']],
    ];

    $months = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
@endphp

@section('content')
<div class="text-white space-y-6" style="
    --primary: var(--primary-color, #22d3ee);
    --secondary: var(--secondary-color, #0f1827);
    --panel: color-mix(in srgb, var(--secondary) 85%, #000 15%);
    --card: color-mix(in srgb, var(--secondary) 78%, #000 22%);
    --border: rgba(255,255,255,0.08);
    --border-strong: rgba(255,255,255,0.12);
    --muted: #cbd5e1;
">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-gray-400">Programacion</p>
            <h1 class="text-3xl font-semibold text-gray-900">Crear malla operativa</h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('company.programming.index') }}" class="px-4 py-2 border border-gray-700 text-gray-200 rounded-md hover:bg-gray-800 transition">Volver</a>
            <button class="px-4 py-2 rounded-md shadow-sm opacity-60 cursor-not-allowed" style="background: var(--primary); color: #0b1220;">Guardar (pr?ximamente)</button>
        </div>
    </div>

    <div class="rounded-xl shadow-lg p-5 space-y-4" style="background: var(--panel); border: 1px solid var(--border);">

        <div class="flex justify-center gap-3 pb-4">
            <button class="px-4 py-2 text-xs font-semibold rounded-md border border-slate-700 hover:bg-slate-800 transition">Crear ítem</button>
            <button class="px-4 py-2 text-xs font-semibold rounded-md border border-slate-700 hover:bg-slate-800 transition">Editar ítem</button>
            <button class="px-4 py-2 text-xs font-semibold rounded-md border border-slate-700 hover:bg-slate-800 transition">Eliminar ítem</button>
        </div>

        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($items as $item)
                <div class="rounded-lg" style="background: var(--card); border: 1px solid var(--border);">
                    <div class="p-4 text-sm text-gray-200">
                        <div class="grid grid-cols-2 gap-3">
                            @for ($t = 0; $t < 4; $t++)
                                <table class="w-full text-xs text-left border border-slate-800/60 rounded-md overflow-hidden">
                                    <tbody>
                                        @for ($r = 0; $r < 1; $r++)
                                            <tr>
                                                <td class="px-2 py-2 border-b border-slate-800/40 bg-slate-900/40">Letra del turno</td>
                                                <td class="px-2 py-2 border-b border-slate-800/40 text-gray-100">Horario / Color</td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            @endfor
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="rounded-xl shadow-lg p-5 space-y-0 overflow-hidden" style="background: var(--panel); border: 1px solid var(--border);">
        <div class="overflow-x-auto" style="background: var(--card); border: 1px solid var(--border); border-top: 0;">
            <table class="min-w-full text-xs text-gray-200">
                <thead>
                    <tr class="text-gray-300" style="background: var(--panel);">
                        <th class="px-3 py-2 border-b border-slate-800 text-center" colspan="2" rowspan="3">
                            <div class="h-12 w-12 rounded-full flex items-center justify-center text-sm text-gray-400" style="background: var(--panel); border: 1px solid var(--border);">Logo</div>
                        </th>
                        <th class="px-2 py-2 border-b border-slate-800 text-center" colspan="{{ count($days) }}">
                            CONDOMINIO HONTANER DE LAS MERCEDEZ - PORTER?A SERVICIO 24 HORAS
                        </th>
                    </tr>
                    <tr class="text-gray-300" style="background: var(--panel);">
                        <th class="px-2 py-2 border-b border-slate-800 text-center" colspan="{{ count($days) }}">
                           P3 SEGURIDAD LTDA - SELECIONA EL MES Y EL AÑO
                        </th>
                    </tr>
                
                    <tr class="text-gray-300" style="background: var(--panel);">
                        @foreach ($days as $day)
                            <th class="px-2 py-2 border-b border-slate-800 text-center">{{ $day->locale('es')->isoFormat('dd') }}</th>
                        @endforeach
                    </tr>
                    <tr class="bg-slate-900/60 text-gray-400">
                        <th class="px-3 py-2 border-b border-slate-800 text-left w-28">C?dula</th>
                        <th class="px-3 py-2 border-b border-slate-800 text-left w-40">Nombre</th>
                        @foreach ($days as $day)
                            <th class="px-2 py-2 border-b border-slate-800 text-center text-[11px]">{{ $day->day }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-3 py-3 border-b border-slate-800 text-sm text-white">10.234.567</td>
                        <td class="px-3 py-3 border-b border-slate-800 text-sm text-white">Nombre del guarda</td>
                        @foreach ($days as $day)
                            <td class="px-1 py-2 border-b border-slate-800 text-center align-top">
                                <select class="w-14 text-gray-100 rounded-md px-1 py-1 text-[11px] focus:ring-1" style="background: var(--panel); border: 1px solid var(--border);">
                                    <option value="">-</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item['code'] }}">{{ $item['code'] }}</option>
                                    @endforeach
                                </select>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

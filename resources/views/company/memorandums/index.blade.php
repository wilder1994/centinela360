@extends('layouts.company')

@section('content')
    <div class="w-full px-4 sm:px-6 lg:px-10 xl:px-16 py-6 space-y-8">
        @php
            // Estadísticas de memorándums por estado (vienen del controlador)
            $draft        = $stats['draft']        ?? 0;
            $inReview     = $stats['in_review']    ?? 0;
            $acknowledged = $stats['acknowledged'] ?? 0;
            $archived     = $stats['archived']     ?? 0;

            // Confirmados = acusados + archivados
            $confirmed = $acknowledged + $archived;

            // Total general
            $total = $draft + $inReview + $confirmed;
        @endphp

        {{-- Encabezado + acciones rápidas --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-semibold">Panel de memorándums</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Estado general de los memorándums y accesos rápidos a las secciones principales.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                {{-- Botón para crear un nuevo memorándum --}}
                <a href="{{ route('company.memorandums.create') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg bg-[var(--primary)] text-white text-sm font-medium hover:bg-blue-700 transition">
                    <span class="mr-1 text-lg leading-none">＋</span>
                    Nuevo memorándum
                </a>
            </div>
        </div>

        {{-- Tarjetas de KPIs / navegación --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 xl:gap-6">
            {{-- Pendientes (draft) -> usa el BOARD con filtro "draft" --}}
            <a href="{{ route('company.memorandums.board', ['status' => 'draft']) }}"
               class="flex flex-col justify-between rounded-2xl border border-blue-100 bg-white p-4 sm:p-5 shadow-sm hover:border-blue-300 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            Memorandos pendientes
                        </p>
                        <p class="mt-2 text-3xl font-semibold text-gray-800">
                            {{ number_format($draft) }}
                        </p>
                    </div>
                    <div class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-blue-50">
                        <span class="text-blue-500 text-lg">📝</span>
                    </div>
                </div>
                <p class="mt-3 text-xs font-medium text-blue-600">
                    Ver tablero →
                </p>
            </a>

            {{-- En revisión (in_review) -> usa el MISMO BOARD con filtro "in_review" --}}
            <a href="{{ route('company.memorandums.board', ['status' => 'in_review']) }}"
               class="flex flex-col justify-between rounded-2xl border border-amber-100 bg-white p-4 sm:p-5 shadow-sm hover:border-amber-300 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            Memorandos en revisión
                        </p>
                        <p class="mt-2 text-3xl font-semibold text-gray-800">
                            {{ number_format($inReview) }}
                        </p>
                    </div>
                    <div class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-amber-50">
                        <span class="text-amber-500 text-lg">🔄</span>
                    </div>
                </div>
                <p class="mt-3 text-xs font-medium text-amber-600">
                    Ver tablero →
                </p>
            </a>

            {{-- Finalizados (acusados + archivados) -> usa la vista especial de finalizados --}}
            <a href="{{ route('company.memorandums.finalized') }}"
               class="flex flex-col justify-between rounded-2xl border border-green-100 bg-white p-4 sm:p-5 shadow-sm hover:border-green-300 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            Memorandos finalizados
                        </p>
                        <p class="mt-2 text-3xl font-semibold text-gray-800">
                            {{ number_format($confirmed) }}
                        </p>
                    </div>
                    <div class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-green-50">
                        <span class="text-green-500 text-lg">✅</span>
                    </div>
                </div>
                <p class="mt-3 text-xs font-medium text-green-600">
                    Ver finalizados →
                </p>
            </a>

            {{-- Total (solo informativo, como en P3) --}}
            <div class="flex flex-col justify-between rounded-2xl border border-slate-100 bg-white p-4 sm:p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            Total de memorandos
                        </p>
                        <p class="mt-2 text-3xl font-semibold text-gray-800">
                            {{ number_format($total) }}
                        </p>
                    </div>
                    <div class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-50">
                        <span class="text-slate-500 text-lg">📊</span>
                    </div>
                </div>
                <p class="mt-3 text-xs text-gray-400">
                    Suma de pendientes, en revisión y finalizados.
                </p>
            </div>
        </div>

        {{-- Zona de indicadores y últimos movimientos --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 xl:gap-8">
            {{-- Indicadores por colaborador --}}
            <div class="rounded-2xl border bg-white p-4 sm:p-5 shadow-sm">
                <h2 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-slate-500 text-sm">
                        👥
                    </span>
                    Indicadores por colaborador
                </h2>

                @if(isset($indicatorsByEmployee) && $indicatorsByEmployee->count())
                    <div class="border rounded-xl overflow-hidden">
                        <table class="min-w-full text-xs">
                            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wide">
                                <tr>
                                    <th class="px-3 py-2 text-left">Colaborador</th>
                                    <th class="px-3 py-2 text-center">Borrador</th>
                                    <th class="px-3 py-2 text-center">En rev.</th>
                                    <th class="px-3 py-2 text-center">Confirm.</th>
                                    <th class="px-3 py-2 text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach ($indicatorsByEmployee as $row)
                                    @php
                                        $name = $row->employee_name ?? 'Sin colaborador';
                                        $initial = mb_substr($name, 0, 1, 'UTF-8');
                                    @endphp
                                    <tr>
                                        <td class="px-3 py-2 text-xs text-gray-700">
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 text-[11px] font-semibold text-slate-600">
                                                    {{ $initial }}
                                                </span>
                                                <span class="truncate">
                                                    {{ $name }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 text-center text-gray-700">
                                            {{ $row->draft ?? 0 }}
                                        </td>
                                        <td class="px-3 py-2 text-center text-gray-700">
                                            {{ $row->in_review ?? 0 }}
                                        </td>
                                        <td class="px-3 py-2 text-center text-gray-700">
                                            {{ $row->confirmed ?? 0 }}
                                        </td>
                                        <td class="px-3 py-2 text-center font-semibold text-gray-900">
                                            {{ $row->total ?? 0 }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-xs text-gray-500">
                        Aún no hay datos suficientes para mostrar indicadores por colaborador.
                    </p>
                @endif
            </div>

            {{-- Últimos movimientos --}}
            <div class="rounded-2xl border bg-white p-4 sm:p-5 shadow-sm">
                <h2 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-slate-500 text-sm">
                        🕒
                    </span>
                    Últimos movimientos
                </h2>

                @if(isset($latestMemorandums) && $latestMemorandums->count())
                    <ul class="divide-y text-xs">
                        @foreach ($latestMemorandums as $history)
                            <li class="py-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div class="space-y-1">
                                    <p class="font-medium text-gray-800">
                                        {{ $history->memorandum->code ?? 'MEM' }} · {{ $history->memorandum->subject ?? 'Sin asunto' }}
                                    </p>
                                    <p class="text-[11px] text-gray-500">
                                        {{ optional($history->created_at)->format('d/m/Y H:i') }}
                                        ·
                                        Cambio a:
                                        <span class="font-medium text-gray-700">
                                            {{ $history->to_status?->label() ?? 'Desconocido' }}
                                        </span>
                                        · Por:
                                        <span class="font-medium text-gray-700">
                                            {{ $history->changer?->name ?? 'Desconocido' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    @php
                                        $status = $history->to_status?->value ?? null;
                                        $badgeClasses = match($status) {
                                            'draft'        => 'bg-blue-100 text-blue-700',
                                            'in_review'    => 'bg-amber-100 text-amber-700',
                                            'acknowledged' => 'bg-emerald-100 text-emerald-700',
                                            'archived'     => 'bg-slate-100 text-slate-700',
                                            default        => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium {{ $badgeClasses }}">
                                        {{ $history->to_status?->label() ?? 'N/A' }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-xs text-gray-500">
                        No se han registrado movimientos recientes de memorándums.
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection

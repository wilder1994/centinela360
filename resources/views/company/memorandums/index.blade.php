@extends('layouts.company')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-10 xl:px-16 py-6 space-y-8">
    @php
        $pendiente  = $stats['pendiente']   ?? 0;
        $enProceso  = $stats['en_proceso']  ?? 0;
        $finalizado = $stats['finalizado']  ?? 0;
        $total      = $pendiente + $enProceso + $finalizado;
    @endphp

    {{-- Encabezado --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-semibold text-gray-900">Panel de control</h1>
            <p class="mt-1 text-sm text-gray-500">
                Estado general de los memorandos y accesos rápidos a las secciones principales.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('company.memorandums.create') }}"
               class="inline-flex items-center px-4 py-2 rounded-lg bg-[var(--primary)] text-white text-sm font-medium hover:opacity-90 transition shadow">
                <span class="icon-tight icon-safe bg-white/10 border-white/20">
                    <x-icon name="doc-plus" class="w-4 h-4" />
                </span>
                <span class="ml-2">Nuevo memorando</span>
            </a>
            <button type="button" data-subjects-modal
                class="inline-flex items-center px-4 py-2 rounded-lg border border-[var(--primary)] text-[var(--primary)] text-sm font-medium hover:bg-[var(--primary)]/10 transition">
                <span class="icon-tight icon-safe bg-[var(--primary)]/10 border-[var(--primary)]/20">
                    <x-icon name="tag" class="w-4 h-4" />
                </span>
                <span class="ml-2">Asuntos</span>
            </button>
        </div>
    </div>

    {{-- Tarjetas de KPIs (iconos via <x-icon> para mantener paleta neutra sin heredar colores de empresa) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 xl:gap-6">
        {{-- Pendientes --}}
        <a href="{{ route('company.memorandums.pendientes') }}"
           class="flex flex-col justify-between rounded-2xl border border-blue-100 bg-white p-4 sm:p-5 shadow-sm hover:border-blue-300 hover:shadow-md transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Memorandos pendientes
                    </p>
                    <p class="mt-2 text-3xl font-semibold text-gray-800">
                        {{ number_format($pendiente) }}
                    </p>
                </div>
                <div class="icon-tight icon-safe bg-blue-50 border-blue-100">
                    <x-icon name="clock" class="w-4 h-4" />
                </div>
            </div>
            <p class="mt-3 text-xs text-[var(--primary)] font-semibold">Ver listado →</p>
        </a>

        {{-- En proceso --}}
        <a href="{{ route('company.memorandums.en_proceso') }}"
           class="flex flex-col justify-between rounded-2xl border border-amber-100 bg-white p-4 sm:p-5 shadow-sm hover:border-amber-300 hover:shadow-md transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Memorandos en proceso
                    </p>
                    <p class="mt-2 text-3xl font-semibold text-gray-800">
                        {{ number_format($enProceso) }}
                    </p>
                </div>
                <div class="icon-tight icon-safe bg-amber-50 border-amber-100">
                    <x-icon name="progress" class="w-4 h-4" />
                </div>
            </div>
            <p class="mt-3 text-xs text-[var(--primary)] font-semibold">Ver listado →</p>
        </a>

        {{-- Finalizados --}}
        <a href="{{ route('company.memorandums.finalizados') }}"
           class="flex flex-col justify-between rounded-2xl border border-green-100 bg-white p-4 sm:p-5 shadow-sm hover:border-green-300 hover:shadow-md transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Memorandos finalizados
                    </p>
                    <p class="mt-2 text-3xl font-semibold text-gray-800">
                        {{ number_format($finalizado) }}
                    </p>
                </div>
                <div class="icon-tight icon-safe bg-green-50 border-green-100">
                    <x-icon name="check" class="w-4 h-4" />
                </div>
            </div>
            <p class="mt-3 text-xs text-[var(--primary)] font-semibold">Ver listado →</p>
        </a>

        {{-- Total --}}
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
                <div class="icon-tight icon-safe bg-slate-50 border-slate-200">
                    <x-icon name="memo" class="w-4 h-4" />
                </div>
            </div>
            <p class="mt-3 text-xs text-gray-400">
                Suma de pendientes, en proceso y finalizados.
            </p>
        </div>
    </div>

    {{-- Zona de indicadores y últimos movimientos --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 xl:gap-8">
        {{-- Indicadores por usuario --}}
        <div class="rounded-2xl border bg-white p-4 sm:p-5 shadow-sm">
            <h2 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <span class="icon-tight icon-safe bg-slate-100 border-slate-200">
                    <x-icon name="users" class="w-4 h-4" />
                </span>
                Indicadores por usuario
            </h2>

            @if(isset($indicators) && $indicators->count())
                <div class="border rounded-xl overflow-hidden">
                    <table class="min-w-full text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase tracking-wide">
                            <tr>
                                <th class="px-3 py-2 text-left">Usuario</th>
                                <th class="px-3 py-2 text-center">Pend.</th>
                                <th class="px-3 py-2 text-center">Proc.</th>
                                <th class="px-3 py-2 text-center">Fin.</th>
                                <th class="px-3 py-2 text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($indicators as $row)
                                @php
                                    $nombre = $row->usuario?->name ?? 'Sin responsable';
                                    $inicial = mb_substr($nombre, 0, 1, 'UTF-8');
                                @endphp
                                <tr>
                                    <td class="px-3 py-2 text-xs text-gray-700">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 text-[11px] font-semibold text-slate-600">
                                                {{ $inicial }}
                                            </span>
                                            <span class="truncate">
                                                {{ $nombre }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 text-center text-gray-700">
                                        {{ $row->pendientes }}
                                    </td>
                                    <td class="px-3 py-2 text-center text-gray-700">
                                        {{ $row->en_proceso }}
                                    </td>
                                    <td class="px-3 py-2 text-center text-gray-700">
                                        {{ $row->finalizados }}
                                    </td>
                                    <td class="px-3 py-2 text-center font-semibold text-gray-900">
                                        {{ $row->total }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-xs text-gray-500">
                    Aún no hay datos de responsables asignados para mostrar indicadores.
                </p>
            @endif
        </div>

        {{-- Últimos movimientos --}}
        <div class="rounded-2xl border bg-white p-4 sm:p-5 shadow-sm">
            <h2 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <span class="icon-tight icon-safe bg-slate-100 border-slate-200">
                    <x-icon name="memo" class="w-4 h-4" />
                </span>
                Últimos movimientos
            </h2>

            @if(isset($latestTickets) && $latestTickets->count())
                <ul class="divide-y text-xs">
                    @foreach ($latestTickets as $memo)
                        <li class="py-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div class="space-y-1">
                                <p class="font-medium text-gray-800">
                                    {{ $memo->title ?? $memo->titulo ?? 'Sin asunto' }}
                                </p>
                                <p class="text-xs text-slate-500 mt-1">
                                    {{ $memo->created_at->format('d/m/Y H:i') }}
                                    · Usuario: {{ $memo->author?->name ?? 'N/D' }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                @php
                                    $estado = $memo->estado ?? 'desconocido';
                                    $badgeClasses = match($estado) {
                                        'pendiente'   => 'bg-blue-100 text-blue-700',
                                        'en_proceso'  => 'bg-amber-100 text-amber-700',
                                        'finalizado'  => 'bg-green-100 text-green-700',
                                        'validacion'  => 'bg-sky-100 text-sky-700',
                                        default       => 'bg-slate-100 text-slate-600',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium {{ $badgeClasses }}">
                                    {{ ucfirst(str_replace('_', ' ', $estado)) }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-xs text-gray-500">
                    No hay registros recientes de memorandos.
                </p>
            @endif
        </div>
    </div>

    {{-- Modal de asuntos --}}
    <div id="subjects-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-30">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-xl p-6 relative">
            <button type="button" data-subjects-close class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">&times;</button>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Asuntos</h3>
            <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
                @forelse ($subjects as $subject)
                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('company.memorandum_subjects.update', $subject) }}" class="flex-1 flex items-center gap-2">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $subject->name }}" class="input w-full rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
                            <button type="submit" class="px-3 py-2 bg-[var(--primary)] text-white rounded-lg text-sm">Guardar</button>
                        </form>
                        <form method="POST" action="{{ route('company.memorandum_subjects.destroy', $subject) }}" onsubmit="return confirm('¿Eliminar asunto?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 text-sm">Eliminar</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No hay asuntos configurados aún.</p>
                @endforelse
            </div>
            <form method="POST" action="{{ route('company.memorandum_subjects.store') }}" class="mt-4 space-y-2">
                @csrf
                <label class="block text-sm font-medium text-gray-700">Nuevo asunto</label>
                <div class="flex items-center gap-2">
                    <input type="text" name="name" class="input w-full rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
                    <button type="submit" class="px-4 py-2 bg-[var(--primary)] text-white rounded-lg">Crear</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Estadísticas por asunto (aprobados / negados) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="rounded-2xl border bg-white p-4 sm:p-5 shadow-sm">
            <h2 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <span class="icon-tight icon-safe bg-green-50 border-green-100">
                    <x-icon name="check" class="w-4 h-4" />
                </span>
                Memorandos aprobados por asunto
            </h2>
            @if(($approvedBySubject ?? collect())->count())
                <div class="border rounded-xl overflow-hidden">
                    <table class="min-w-full text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase tracking-wide">
                            <tr>
                                <th class="px-3 py-2 text-left">Asunto</th>
                                <th class="px-3 py-2 text-center">Aprobados</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($approvedBySubject as $row)
                                <tr>
                                    <td class="px-3 py-2 text-gray-700">{{ $row->title ?? 'Sin asunto' }}</td>
                                    <td class="px-3 py-2 text-center font-semibold text-green-700">{{ $row->total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-xs text-gray-500">No hay memorandos aprobados aún.</p>
            @endif
        </div>

        <div class="rounded-2xl border bg-white p-4 sm:p-5 shadow-sm">
            <h2 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <span class="icon-tight icon-safe bg-red-50 border-red-100">
                    <x-icon name="close" class="w-4 h-4" />
                </span>
                Memorandos negados por asunto
            </h2>
            @if(($deniedBySubject ?? collect())->count())
                <div class="border rounded-xl overflow-hidden">
                    <table class="min-w-full text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase tracking-wide">
                            <tr>
                                <th class="px-3 py-2 text-left">Asunto</th>
                                <th class="px-3 py-2 text-center">Negados</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($deniedBySubject as $row)
                                <tr>
                                    <td class="px-3 py-2 text-gray-700">{{ $row->title ?? 'Sin asunto' }}</td>
                                    <td class="px-3 py-2 text-center font-semibold text-red-700">{{ $row->total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-xs text-gray-500">No hay memorandos negados aún.</p>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const subjectsModal = document.getElementById('subjects-modal');
        const shouldOpenSubjects = @json(session('open_memo_subjects', false));

        document.querySelectorAll('[data-subjects-modal]').forEach(button => {
            button.addEventListener('click', () => {
                subjectsModal?.classList.remove('hidden');
                subjectsModal?.classList.add('flex');
            });
        });

        document.querySelectorAll('[data-subjects-close]').forEach(button => {
            button.addEventListener('click', () => {
                subjectsModal?.classList.add('hidden');
                subjectsModal?.classList.remove('flex');
            });
        });

        if (subjectsModal && shouldOpenSubjects) {
            subjectsModal.classList.remove('hidden');
            subjectsModal.classList.add('flex');
        }
    });
</script>
@endpush
@endsection

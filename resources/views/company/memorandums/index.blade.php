@extends('layouts.company')

@section('content')
<div class="space-y-8 animate-fadeIn">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Memorándums</h1>
            <p class="text-sm text-gray-500">Monitorea el ciclo completo de tus memorándums internos y mantén trazabilidad del seguimiento.</p>
        </div>
        <a href="{{ route('company.memorandums.create') }}"
           class="inline-flex items-center justify-center rounded-lg bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-600 transition-colors">
            + Registrar memorándum
        </a>
    </div>

    @php
        $statusCounts = $statusCounts ?? collect();
    @endphp

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl bg-white p-6 shadow">
            <p class="text-xs font-semibold uppercase text-gray-500">Total memorándums</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalMemorandums }}</p>
        </div>
        @foreach (\App\Enums\MemorandumStatus::cases() as $status)
            <div class="rounded-xl bg-white p-6 shadow">
                <p class="text-xs font-semibold uppercase text-gray-500">{{ $status->label() }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $statusCounts[$status->value] ?? 0 }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid gap-6 lg:grid-cols-[2fr_1fr]">
        <div class="space-y-4">
            <form method="GET" action="{{ route('company.memorandums.index') }}"
                  class="grid gap-3 rounded-xl bg-white p-4 shadow md:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label for="search" class="block text-xs font-semibold text-gray-500">Buscar</label>
                    <input id="search" name="search" type="text" value="{{ $filters['search'] ?? '' }}"
                           placeholder="Asunto, colaborador..."
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                </div>
                <div>
                    <label for="status" class="block text-xs font-semibold text-gray-500">Estado</label>
                    <select id="status" name="status"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                        <option value="">Todos</option>
                        @foreach ($statusOptions as $option)
                            <option value="{{ $option['value'] }}" @selected(($filters['status'] ?? '') === $option['value'])>
                                {{ $option['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="date_from" class="block text-xs font-semibold text-gray-500">Desde</label>
                    <input id="date_from" name="date_from" type="date" value="{{ $filters['date_from'] ?? '' }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                </div>
                <div>
                    <label for="date_to" class="block text-xs font-semibold text-gray-500">Hasta</label>
                    <div class="flex items-center gap-2">
                        <input id="date_to" name="date_to" type="date" value="{{ $filters['date_to'] ?? '' }}"
                               class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                        @if (array_filter($filters))
                            <a href="{{ route('company.memorandums.index') }}" class="text-xs text-gray-500 hover:text-gray-700">Limpiar</a>
                        @endif
                    </div>
                </div>
                <div class="md:col-span-2 lg:col-span-4 flex justify-end">
                    <button type="submit"
                            class="rounded-lg bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-600 transition-colors">
                        Aplicar filtros
                    </button>
                </div>
            </form>

            <div class="rounded-xl bg-white shadow">
                <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Listado de memorándums</h2>
                    <span class="text-sm text-gray-500">{{ $memorandums->total() }} resultados</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-gray-700">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="px-6 py-3 text-left">Código</th>
                                <th class="px-6 py-3 text-left">Colaborador</th>
                                <th class="px-6 py-3 text-left">Asunto</th>
                                <th class="px-6 py-3 text-center">Estado</th>
                                <th class="px-6 py-3 text-center">Emitido</th>
                                <th class="px-6 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($memorandums as $memorandum)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $memorandum->code }}</td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-800">{{ $memorandum->employee?->full_name ?? 'No asignado' }}</div>
                                        <div class="text-xs text-gray-500">{{ $memorandum->employee?->position }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-800">{{ $memorandum->subject }}</div>
                                        <p class="text-xs text-gray-500 line-clamp-2">{{ \Illuminate\Support\Str::limit($memorandum->body, 120) }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center rounded-full px-3 py-1 text-xs font-semibold {{ $memorandum->status->badgeClasses() }}">
                                            {{ $memorandum->status->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-600">
                                        {{ optional($memorandum->issued_at)->format('d M Y') ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm">
                                        <div class="flex items-center justify-center gap-3">
                                            <a href="{{ route('company.memorandums.show', $memorandum) }}"
                                               class="text-[var(--primary)] hover:underline">Ver</a>
                                            <span class="text-gray-300">|</span>
                                            <a href="{{ route('company.memorandums.edit', $memorandum) }}"
                                               class="text-[var(--primary)] hover:underline">Editar</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                                        Aún no hay memorándums registrados. Crea el primero para comenzar el seguimiento.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-gray-100 px-6 py-4">
                    {{ $memorandums->links() }}
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="rounded-xl bg-white p-6 shadow">
                <h2 class="text-lg font-semibold text-gray-800">Actualizaciones recientes</h2>
                <p class="mt-1 text-sm text-gray-500">Últimos cambios de estado registrados en la compañía.</p>
                <div class="mt-4 space-y-4">
                    @forelse ($recentUpdates as $history)
                        <div class="flex items-start gap-3">
                            <div class="mt-1 h-2.5 w-2.5 flex-shrink-0 rounded-full bg-[var(--primary)]"></div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $history->memorandum->code }} · {{ $history->memorandum->subject }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    Cambio a <span class="font-semibold">{{ $history->to_status?->label() }}</span>
                                    — {{ $history->created_at?->diffForHumans() }} por {{ $history->changer?->name }}
                                </p>
                                @if ($history->notes)
                                    <p class="mt-1 text-xs text-gray-600">{{ $history->notes }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Todavía no se han registrado cambios de estado.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl bg-white p-6 shadow">
                <h2 class="text-lg font-semibold text-gray-800">Consejos rápidos</h2>
                <ul class="mt-3 space-y-2 text-sm text-gray-600">
                    <li>• Utiliza la columna de estado para priorizar los memorándums en revisión.</li>
                    <li>• Actualiza el estado cuando el colaborador confirme la recepción.</li>
                    <li>• Mantén un registro claro de notas para documentar el seguimiento.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@php
    use App\Enums\MemorandumStatus;
    use Illuminate\Support\Str;

    $total = $totalMemorandums ?? 0;
@endphp

<div class="space-y-8">
    {{-- Encabezado --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Tablero de memorándums
            </h1>
            <p class="text-sm text-gray-500">
                Visualiza el ciclo de vida de los memorándums por estado y actúa rápidamente sobre cada caso.
            </p>
        </div>

        <div class="flex flex-col gap-3 md:flex-row md:items-center">
            <a
                href="{{ route('company.memorandums.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50"
            >
                ← Volver a la lista
            </a>

            <a
                href="{{ route('company.memorandums.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-600"
            >
                + Registrar memorándum
            </a>
        </div>
    </div>

    {{-- Tarjetas de métricas --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-100">
            <p class="text-xs font-semibold uppercase text-gray-500">Total memorándums</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $total }}</p>
            <p class="mt-1 text-xs text-gray-400">Incluye todos los estados</p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm border border-blue-50">
            <p class="text-xs font-semibold uppercase text-blue-600">Borradores</p>
            <p class="mt-2 text-3xl font-bold text-blue-700">{{ $stats['draft'] ?? 0 }}</p>
            <p class="mt-1 text-xs text-gray-400">Pendientes de revisión</p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm border border-amber-50">
            <p class="text-xs font-semibold uppercase text-amber-600">En revisión</p>
            <p class="mt-2 text-3xl font-bold text-amber-700">{{ $stats['in_review'] ?? 0 }}</p>
            <p class="mt-1 text-xs text-gray-400">Activos en seguimiento</p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm border border-emerald-50">
            <p class="text-xs font-semibold uppercase text-emerald-600">Confirmados</p>
            <p class="mt-2 text-3xl font-bold text-emerald-700">
                {{ ($stats['acknowledged'] ?? 0) + ($stats['archived'] ?? 0) }}
            </p>
            <p class="mt-1 text-xs text-gray-400">Acusados de recibo o archivados</p>
        </div>
    </div>

    {{-- Filtros de búsqueda --}}
    <div class="rounded-xl bg-white p-4 shadow-sm border border-gray-100">
        <div class="grid gap-3 md:grid-cols-[2fr,1.5fr] lg:grid-cols-[2fr,1.5fr,1fr] md:items-end">
            {{-- Buscador --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">
                    Buscar
                </label>
                <div class="relative">
                    <input
                        type="text"
                        wire:model.debounce.500ms="search"
                        placeholder="Asunto, contenido o empleado..."
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 pr-8 text-sm shadow-sm focus:border-[var(--primary)] focus:outline-none focus:ring-1 focus:ring-[var(--primary)]"
                    >
                    <span class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-400">
                        🔍
                    </span>
                </div>
            </div>

            {{-- Filtro por empleado --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">
                    Empleado
                </label>
                <select
                    wire:model="employeeId"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-[var(--primary)] focus:outline-none focus:ring-1 focus:ring-[var(--primary)]"
                >
                    <option value="">Todos los empleados</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}">
                            {{ $employee->first_name }} {{ $employee->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Info resumida --}}
            <div class="hidden lg:flex lg:flex-col lg:items-end lg:justify-center">
                <p class="text-xs text-gray-500">
                    Mostrando <span class="font-semibold text-gray-700">{{ $total }}</span> memorándum(s)
                    según los filtros actuales.
                </p>
            </div>
        </div>
    </div>

    {{-- Tablero tipo kanban --}}
    <div class="rounded-xl bg-white p-4 shadow-sm border border-gray-100">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-700">
                Flujo por estado
            </h2>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($columns as $status => $memorandums)
                @php
                    $statusEnum = MemorandumStatus::from($status);
                @endphp

                <div class="flex flex-col rounded-xl border border-gray-200 bg-gray-50/80 p-3 shadow-sm min-h-[220px]">
                    {{-- Header de la columna --}}
                    <div class="mb-3 flex items-center justify-between gap-2">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800">
                                {{ $statusEnum->label() }}
                            </h3>
                            <p class="text-xs text-gray-400">
                                {{ $memorandums->count() }} memorándum(s)
                            </p>
                        </div>

                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold
                            @class([
                                'bg-blue-100 text-blue-700' => $status === 'draft',
                                'bg-amber-100 text-amber-700' => $status === 'in_review',
                                'bg-emerald-100 text-emerald-700' => $status === 'acknowledged',
                                'bg-slate-100 text-slate-700' => $status === 'archived',
                                'bg-gray-100 text-gray-700' => ! in_array($status, ['draft', 'in_review', 'acknowledged', 'archived']),
                            ])
                        ">
                            {{ strtoupper($status) }}
                        </span>
                    </div>

                    {{-- Lista de tarjetas --}}
                    <div class="flex-1 space-y-3 overflow-y-auto">
                        @forelse ($memorandums as $memorandum)
                            @php
                                $currentStatus = $memorandum->status instanceof MemorandumStatus
                                    ? $memorandum->status->value
                                    : $memorandum->status;
                            @endphp

                            <div class="rounded-lg bg-white p-3 shadow-sm ring-1 ring-gray-200 transition hover:shadow-md hover:-translate-y-0.5">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <p class="text-xs font-mono text-gray-400">
                                            {{ $memorandum->code }}
                                        </p>
                                        <h4 class="text-sm font-semibold text-gray-800">
                                            {{ $memorandum->subject }}
                                        </h4>
                                    </div>
                                </div>

                                <div class="mt-2 space-y-1 text-xs text-gray-500">
                                    @if ($memorandum->employee)
                                        <p>
                                            👤
                                            {{ $memorandum->employee->first_name }}
                                            {{ $memorandum->employee->last_name }}
                                        </p>
                                    @endif

                                    <p class="line-clamp-2">
                                        {{ Str::limit($memorandum->body, 120) }}
                                    </p>

                                    <p class="text-[11px] text-gray-400">
                                        Estado:
                                        <span class="font-medium text-gray-700">
                                            {{ $memorandum->status_label }}
                                        </span>
                                    </p>

                                    @if ($memorandum->issued_at)
                                        <p class="text-[11px] text-gray-400">
                                            Emitido: {{ $memorandum->issued_at->format('d/m/Y H:i') }}
                                        </p>
                                    @endif

                                    @if ($memorandum->acknowledged_at)
                                        <p class="text-[11px] text-gray-400">
                                            Recibido: {{ $memorandum->acknowledged_at->format('d/m/Y H:i') }}
                                        </p>
                                    @endif
                                </div>

                                <div class="mt-3 flex items-center justify-between gap-2">
                                    {{-- Botones de cambio de estado --}}
                                    <div class="flex flex-wrap gap-2">
                                        @if ($currentStatus === 'draft')
                                            <button
                                                wire:click="changeStatus({{ $memorandum->id }}, 'in_review')"
                                                class="inline-flex items-center rounded-md bg-indigo-600 px-2.5 py-1 text-xs font-semibold text-white hover:bg-indigo-700"
                                            >
                                                ▶ Enviar a revisión
                                            </button>
                                        @elseif ($currentStatus === 'in_review')
                                            <button
                                                wire:click="changeStatus({{ $memorandum->id }}, 'acknowledged')"
                                                class="inline-flex items-center rounded-md bg-emerald-600 px-2.5 py-1 text-xs font-semibold text-white hover:bg-emerald-700"
                                            >
                                                ✔ Marcar recibido
                                            </button>
                                        @elseif ($currentStatus === 'acknowledged')
                                            <button
                                                wire:click="changeStatus({{ $memorandum->id }}, 'archived')"
                                                class="inline-flex items-center rounded-md bg-slate-600 px-2.5 py-1 text-xs font-semibold text-white hover:bg-slate-700"
                                            >
                                                📁 Archivar
                                            </button>
                                        @endif
                                    </div>

                                    {{-- Link a detalle --}}
                                    <a
                                        href="{{ route('company.memorandums.show', $memorandum) }}"
                                        class="text-xs font-medium text-indigo-600 hover:text-indigo-800"
                                    >
                                        Ver detalle →
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400">
                                No hay memorándums en este estado con los filtros actuales.
                            </p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

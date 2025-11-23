@php
    use Illuminate\Support\Str;
@endphp

<div class="space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Memorándums finalizados</h1>
            <p class="text-sm text-gray-500">Historial de memorándums confirmados o archivados.</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('company.memorandums.index') }}"
                class="inline-flex items-center px-3 py-1.5 rounded-full border border-slate-200 bg-white text-[11px] sm:text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                ← Volver al listado
            </a>
        </div>
    </div>

    {{-- STATS --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-xl bg-white p-6 shadow">
            <p class="text-xs font-semibold uppercase text-gray-500">Total finalizados</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>

        <div class="rounded-xl bg-white p-6 shadow">
            <p class="text-xs font-semibold uppercase text-gray-500">Confirmados</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['acknowledged'] }}</p>
        </div>

        <div class="rounded-xl bg-white p-6 shadow">
            <p class="text-xs font-semibold uppercase text-gray-500">Archivados</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['archived'] }}</p>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="rounded-xl bg-white p-6 shadow space-y-4">
        <div class="grid gap-3 md:grid-cols-3">
            <input wire:model.live.debounce.600ms="search"
                   type="text"
                   placeholder="Buscar por asunto, cuerpo o empleado..."
                   class="rounded-lg border-gray-300 focus:ring-[var(--primary)] focus:border-[var(--primary)]" />

            <select wire:model.live="status"
                    class="rounded-lg border-gray-300 focus:ring-[var(--primary)] focus:border-[var(--primary)]">
                <option value="">Todos</option>
                <option value="acknowledged">Confirmados</option>
                <option value="archived">Archivados</option>
            </select>

            <select wire:model.live="employeeId"
                    class="rounded-lg border-gray-300 focus:ring-[var(--primary)] focus:border-[var(--primary)]">
                <option value="">Todos los empleados</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid gap-3 md:grid-cols-3">
            <input type="date"
                   wire:model.live="date_from"
                   class="rounded-lg border-gray-300 focus:ring-[var(--primary)] focus:border-[var(--primary)]" />

            <input type="date"
                   wire:model.live="date_to"
                   class="rounded-lg border-gray-300 focus:ring-[var(--primary)] focus:border-[var(--primary)]" />

            <button wire:click="clearFilters"
                    class="rounded-lg bg-gray-200 px-4 py-2 text-sm font-medium shadow hover:bg-gray-300">
                Limpiar filtros
            </button>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="rounded-xl bg-white shadow">
        <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Listado de finalizados</h2>
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
                        <th class="px-6 py-3 text-center">Actualización</th>
                        <th class="px-6 py-3 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($memorandums as $memorandum)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono text-gray-900">{{ $memorandum->code }}</td>

                            <td class="px-6 py-4">
                                {{ $memorandum->employee?->full_name ?? '—' }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">{{ $memorandum->subject }}</div>
                                <p class="text-xs text-gray-500 line-clamp-2">
                                    {{ Str::limit($memorandum->body, 100) }}
                                </p>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center rounded-full px-3 py-1 text-xs font-semibold {{ $memorandum->status->badgeClasses() }}">
                                    {{ $memorandum->status->label() }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center text-sm">
                                {{ optional($memorandum->issued_at)->format('d/m/Y') ?? '—' }}
                            </td>

                            <td class="px-6 py-4 text-center text-xs text-gray-500">
                                @php
                                    $last = $memorandum->latestStatusHistory->first();
                                @endphp

                                @if($last)
                                    {{ $last->created_at->diffForHumans() }}
                                    <br>
                                    <span class="text-gray-400">
                                        por {{ $last->changer?->name }}
                                    </span>
                                @else
                                    —
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('company.memorandums.show', $memorandum) }}"
                                   class="text-[var(--primary)] hover:underline">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">
                                No hay memorándums finalizados.
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

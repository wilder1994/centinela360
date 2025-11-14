@php
    use Illuminate\Support\Str;
    use App\Enums\MemorandumStatus;
@endphp

<div class="flex flex-col gap-4 h-full w-full">

    {{-- Barra superior: volver al listado + buscador + tarjetas --}}
    <div class="sticky top-0 z-10 space-y-3 bg-slate-50/80 backdrop-blur pb-3">

        {{-- 🔙 Volver al listado principal de memorándums --}}
        <div class="flex justify-end">
            <a href="{{ route('company.memorandums.index') }}"
               class="inline-flex items-center px-3 py-1.5 rounded-full border border-slate-200 bg-white text-[11px] sm:text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                ← Volver al listado
            </a>
        </div>

        {{-- 🔍 Buscador --}}
        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm px-3 py-2 sm:px-4 sm:py-3">
            <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
                <div class="flex-1 flex items-center gap-2">
                    <input
                        type="text"
                        wire:model.live="search"
                        wire:input.debounce.500ms="filtrar"
                        placeholder="Buscar por asunto, contenido o colaborador..."
                        autocomplete="off"
                        spellcheck="true"
                        class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>
            </div>
        </div>

        {{-- 📊 Tarjetas resumen por estado --}}
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            @foreach ([
                'draft'        => ['label' => 'Borradores',        'color' => 'blue',   'icon' => '📝', 'status' => 'draft'],
                'in_review'    => ['label' => 'En revisión',       'color' => 'amber',  'icon' => '🔄', 'status' => 'in_review'],
                'acknowledged' => ['label' => 'Acusados',          'color' => 'green',  'icon' => '✅', 'status' => 'acknowledged'],
                'archived'     => ['label' => 'Archivados',        'color' => 'slate',  'icon' => '📁', 'status' => 'archived'],
            ] as $key => $info)
                @php
                    $count    = $conteos[$key] ?? 0;
                    $isActive = ($filtroEstado ?? null) === $key;

                    $baseBorder = match($info['color']) {
                        'blue'  => 'border-blue-100',
                        'amber' => 'border-amber-100',
                        'green' => 'border-green-100',
                        'slate' => 'border-slate-100',
                        default => 'border-slate-100',
                    };

                    $iconBg = match($info['color']) {
                        'blue'  => 'bg-blue-50 text-blue-500',
                        'amber' => 'bg-amber-50 text-amber-500',
                        'green' => 'bg-green-50 text-green-500',
                        'slate' => 'bg-slate-50 text-slate-500',
                        default => 'bg-slate-50 text-slate-500',
                    };

                    $borderClass = $isActive ? 'border-blue-400 shadow-md' : $baseBorder;
                @endphp

                {{-- La tarjeta simplemente aplica un filtro de estado en el board --}}
                <button
                    type="button"
                    wire:click="filtrarPorEstado('{{ $info['status'] }}')"
                    class="bg-white rounded-2xl border {{ $borderClass }} shadow-sm px-4 py-3 flex items-center justify-between w-full transition hover:shadow-md"
                >
                    <div class="text-left">
                        <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                            {{ $info['label'] }}
                        </p>
                        <p class="mt-1 text-2xl font-semibold text-gray-800">
                            {{ $count }}
                        </p>
                    </div>
                    <div class="inline-flex h-9 w-9 items-center justify-center rounded-full {{ $iconBg }}">
                        <span class="text-lg">{{ $info['icon'] }}</span>
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    {{-- 📋 Listado general de memorándums --}}
    <div class="flex-1 bg-white shadow-sm rounded-2xl border border-slate-100 p-3 sm:p-4 overflow-auto">
        <h3 class="font-semibold mb-3 text-gray-800 text-sm sm:text-base">
            {{ $tituloTabla ?? 'Listado de memorándums' }}
        </h3>

        <div class="w-full overflow-x-auto">
            <table class="w-full text-xs sm:text-sm border-collapse table-auto">
                <thead>
                    <tr class="bg-slate-50 text-slate-600">
                        <th class="p-2 border text-left w-32 whitespace-nowrap">Fecha</th>
                        <th class="p-2 border text-left w-40 whitespace-nowrap">Autor</th>
                        <th class="p-2 border text-left w-48 whitespace-nowrap">Colaborador</th>
                        <th class="p-2 border text-left w-56">Asunto</th>
                        <th class="p-2 border text-left w-[40%]">Contenido</th>
                        <th class="p-2 border text-center w-32">Estado</th>
                        <th class="p-2 border text-center w-24">Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($memorandumsPlanos as $m)
                        @php
                            $fecha = $m->issued_at ?? $m->created_at;
                            $statusEnum = $m->status instanceof MemorandumStatus ? $m->status : MemorandumStatus::from($m->status);

                            $badgeEstadoClasses = match($statusEnum->value) {
                                'draft'        => 'bg-blue-100 text-blue-700',
                                'in_review'    => 'bg-amber-100 text-amber-700',
                                'acknowledged' => 'bg-emerald-100 text-emerald-700',
                                'archived'     => 'bg-slate-100 text-slate-700',
                                default        => 'bg-slate-100 text-slate-600',
                            };
                        @endphp

                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-2 border align-top whitespace-nowrap">
                                {{ optional($fecha)->format('d/m/Y H:i') }}
                            </td>
                            <td class="p-2 border align-top whitespace-nowrap">
                                {{ Str::title(Str::lower($m->author?->name ?? '—')) }}
                            </td>
                            <td class="p-2 border align-top whitespace-nowrap">
                                {{ $m->employee?->full_name ?? 'No asignado' }}
                            </td>
                            <td class="p-2 border align-top">
                                {{ $m->subject }}
                            </td>
                            <td class="p-2 border align-top w-[40%]">
                                <div class="text-[11px] sm:text-xs leading-snug">
                                    {{ Str::limit(strip_tags($m->body), 200) }}
                                </div>
                            </td>
                            <td class="p-2 border text-center align-top">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $badgeEstadoClasses }}">
                                    {{ $statusEnum->label() }}
                                </span>
                            </td>
                            <td class="p-2 border text-center align-top">
                                <a
                                    href="{{ route('company.memorandums.show', $m) }}"
                                    class="px-3 py-1 rounded-lg bg-slate-700 text-white text-xs sm:text-sm hover:bg-slate-800 transition whitespace-nowrap inline-flex items-center justify-center">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-4 text-gray-500">
                                {{ $mensajeVacio ?? 'No hay memorándums registrados con los filtros actuales.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Si usas paginación en el componente, aquí podrías poner los links --}}
        @if (method_exists($memorandumsPlanos, 'links'))
            <div class="mt-3">
                {{ $memorandumsPlanos->links() }}
            </div>
        @endif
    </div>
</div>

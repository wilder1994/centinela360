@extends('layouts.company')

@section('content')
<div class="space-y-6 animate-fadeIn">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $memorandum->code }}</h1>
            <p class="text-sm text-gray-500">{{ $memorandum->subject }}</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $memorandum->status->badgeClasses() }}">
                {{ $memorandum->status->label() }}
            </span>
            @can('update', $memorandum)
                <a href="{{ route('company.memorandums.edit', $memorandum) }}"
                   class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">Editar</a>
            @endcan
        </div>
    </div>

    @if (session('status'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-xl bg-white p-6 shadow">
                <div class="flex flex-col gap-4 md:flex-row md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Detalles del memorándum</h2>
                        <p class="text-xs text-gray-500">Creado el {{ optional($memorandum->issued_at)->format('d M Y') ?? '—' }} por {{ $memorandum->author?->name }}</p>
                    </div>
                    <div class="text-sm text-gray-500">
                        Última actualización {{ optional($memorandum->updated_at)->diffForHumans() }}
                    </div>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2 text-sm text-gray-600">
                    <div>
                        <p class="text-xs font-semibold uppercase text-gray-400">Colaborador</p>
                        <p class="font-medium text-gray-800">{{ $memorandum->employee?->full_name ?? 'No asignado' }}</p>
                        <p class="text-xs text-gray-500">{{ $memorandum->employee?->position }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-gray-400">Estado actual</p>
                        <p class="font-medium text-gray-800">{{ $memorandum->status->label() }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-gray-400">Fecha de emisión</p>
                        <p class="font-medium text-gray-800">{{ optional($memorandum->issued_at)->format('d M Y H:i') ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-gray-400">Fecha de acuse</p>
                        <p class="font-medium text-gray-800">{{ optional($memorandum->acknowledged_at)->format('d M Y H:i') ?? 'Pendiente' }}</p>
                    </div>
                </div>

                <div class="mt-6 border-t border-gray-100 pt-6 text-sm leading-relaxed text-gray-700">
                    {{ $memorandum->body }}
                </div>
            </div>

            <div class="rounded-xl bg-white p-6 shadow">
                <h2 class="text-lg font-semibold text-gray-800">Historial de estados</h2>
                <div class="mt-4">
                    @include('company.memorandums.partials.status-history', ['history' => $memorandum->statusHistories->sortBy('created_at')])
                </div>
            </div>
        </div>

        <div class="space-y-6">
            @can('update', $memorandum)
                <div class="rounded-xl bg-white p-6 shadow">
                    <h2 class="text-lg font-semibold text-gray-800">Actualizar estado</h2>
                    <p class="mt-1 text-xs text-gray-500">Registra el avance y notifica a los responsables.</p>
                    <form method="POST" action="{{ route('company.memorandums.status', $memorandum) }}" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700">Nuevo estado</label>
                            <select id="status" name="status"
                                    class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                                @foreach ($statusOptions as $option)
                                    <option value="{{ $option['value'] }}" @selected($option['value'] === $memorandum->status->value)>{{ $option['label'] }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="notes" class="block text-sm font-semibold text-gray-700">Notas</label>
                            <textarea id="notes" name="notes" rows="3"
                                      class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]"
                                      placeholder="Describe el motivo del cambio o acciones a seguir."></textarea>
                            @error('notes')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                                class="w-full rounded-lg bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-600 transition-colors">
                            Registrar cambio
                        </button>
                    </form>
                </div>
            @endcan

            <div class="rounded-xl bg-white p-6 shadow">
                <h2 class="text-lg font-semibold text-gray-800">Información adicional</h2>
                <ul class="mt-3 space-y-2 text-sm text-gray-600">
                    <li><span class="font-semibold text-gray-700">Responsable:</span> {{ $memorandum->author?->name }}</li>
                    <li><span class="font-semibold text-gray-700">Creado:</span> {{ optional($memorandum->created_at)->format('d M Y H:i') }}</li>
                    <li><span class="font-semibold text-gray-700">Actualizado:</span> {{ optional($memorandum->updated_at)->format('d M Y H:i') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

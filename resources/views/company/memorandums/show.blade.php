@extends('layouts.company')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $memorandum->title }}</h1>
            <p class="text-sm text-gray-500">Estado: {{ ucfirst(str_replace('_',' ', $memorandum->estado)) }} • Prioridad: {{ ucfirst($memorandum->prioridad) }}</p>
        </div>
        <a href="{{ route('company.memorandums.board', $memorandum) }}" class="bg-[var(--primary)] text-white px-4 py-2 rounded-lg shadow">Editar</a>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        <div class="md:col-span-2 bg-white rounded-xl shadow p-6 space-y-4">
            <div class="text-sm text-gray-700 whitespace-pre-line">{{ $memorandum->body ?: 'Sin descripcion' }}</div>
            <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-700">
                <div><span class="font-semibold">Autor:</span> {{ $memorandum->author?->name }}</div>
                <div><span class="font-semibold">Asignado a:</span> {{ $memorandum->assignedTo?->name ?? 'Sin asignar' }}</div>
                <div><span class="font-semibold">Aprobado por:</span> {{ $memorandum->approvedBy?->name ?? 'Sin aprobar' }}</div>
                <div><span class="font-semibold">Vence en:</span> {{ $memorandum->vence_en?->format('Y-m-d H:i') ?? 'Sin fecha' }}</div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-800">Acciones rapidas</h3>
            <form method="POST" action="{{ route('company.memorandums.update', $memorandum) }}" class="space-y-3">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700">Estado</label>
                    <select name="estado" class="w-full rounded-lg border border-gray-200 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                        @foreach ($estadoOptions as $option)
                            <option value="{{ $option }}" @selected($memorandum->estado === $option)>{{ ucfirst(str_replace('_',' ', $option)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Prioridad</label>
                    <select name="prioridad" class="w-full rounded-lg border border-gray-200 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                        @foreach ($prioridadOptions as $option)
                            <option value="{{ $option }}" @selected($memorandum->prioridad === $option)>{{ ucfirst($option) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Asignado a</label>
                    <select name="assigned_to" class="w-full rounded-lg border border-gray-200 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                        <option value="">Sin asignar</option>
                        @foreach ($responsables as $responsable)
                            <option value="{{ $responsable->id }}" @selected($memorandum->assigned_to === $responsable->id)>{{ $responsable->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Aprobado por</label>
                    <select name="approved_by" class="w-full rounded-lg border border-gray-200 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                        <option value="">Sin aprobar</option>
                        @foreach ($responsables as $responsable)
                            <option value="{{ $responsable->id }}" @selected($memorandum->approved_by === $responsable->id)>{{ $responsable->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Vence en</label>
                    <input type="datetime-local" name="vence_en" value="{{ optional($memorandum->vence_en)->format('Y-m-d\TH:i') }}"
                           class="w-full rounded-lg border border-gray-200 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                </div>
                <input type="hidden" name="title" value="{{ $memorandum->title }}">
                <input type="hidden" name="body" value="{{ $memorandum->body }}">
                <button type="submit" class="w-full bg-[var(--primary)] text-white px-4 py-2 rounded-lg">Actualizar</button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Historial</h3>
        @livewire('memorandum-logs', ['memorandum' => $memorandum])
    </div>
</div>
@endsection

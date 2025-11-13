@extends('layouts.company')

@section('content')
<div class="space-y-6 animate-fadeIn">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Editar memorándum</h1>
            <p class="text-sm text-gray-500">Actualiza la información y los responsables del memorándum.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('company.memorandums.show', $memorandum) }}"
               class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">Ver detalle</a>
            <a href="{{ route('company.memorandums.index') }}"
               class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">Volver al listado</a>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <form method="POST" action="{{ route('company.memorandums.update', $memorandum) }}" class="space-y-6 rounded-xl bg-white p-6 shadow lg:col-span-2">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="subject" class="block text-sm font-semibold text-gray-700">Asunto *</label>
                    <input id="subject" name="subject" type="text" value="{{ old('subject', $memorandum->subject) }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]" required>
                    @error('subject')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="employee_id" class="block text-sm font-semibold text-gray-700">Colaborador</label>
                    <select id="employee_id" name="employee_id"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                        <option value="">Sin asignar</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" @selected(old('employee_id', $memorandum->employee_id) == $employee->id)>
                                {{ $employee->full_name }} · {{ $employee->position }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="issued_at" class="block text-sm font-semibold text-gray-700">Fecha de emisión</label>
                    <input id="issued_at" name="issued_at" type="date" value="{{ old('issued_at', optional($memorandum->issued_at)->format('Y-m-d')) }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                    @error('issued_at')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="acknowledged_at" class="block text-sm font-semibold text-gray-700">Fecha de acuse</label>
                    <input id="acknowledged_at" name="acknowledged_at" type="datetime-local" value="{{ old('acknowledged_at', optional($memorandum->acknowledged_at)->format('Y-m-d\TH:i')) }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                    @error('acknowledged_at')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700">Estado</label>
                    <select id="status" name="status"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                        @foreach ($statusOptions as $option)
                            <option value="{{ $option['value'] }}" @selected(old('status', $memorandum->status->value) === $option['value'])>
                                {{ $option['label'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="body" class="block text-sm font-semibold text-gray-700">Descripción *</label>
                <textarea id="body" name="body" rows="6"
                          class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]" required>{{ old('body', $memorandum->body) }}</textarea>
                @error('body')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="notes" class="block text-sm font-semibold text-gray-700">Notas de actualización</label>
                <textarea id="notes" name="notes" rows="3"
                          class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]"
                          placeholder="Describe el motivo de la actualización o pasos siguientes.">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('company.memorandums.show', $memorandum) }}"
                   class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">Cancelar</a>
                <button type="submit"
                        class="rounded-lg bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-600 transition-colors">
                    Guardar cambios
                </button>
            </div>
        </form>

        <div class="space-y-6">
            <div class="rounded-xl bg-white p-6 shadow">
                <h2 class="text-lg font-semibold text-gray-800">Resumen</h2>
                <ul class="mt-3 space-y-2 text-sm text-gray-600">
                    <li><span class="font-semibold text-gray-700">Código:</span> {{ $memorandum->code }}</li>
                    <li><span class="font-semibold text-gray-700">Estado actual:</span> {{ $memorandum->status->label() }}</li>
                    <li><span class="font-semibold text-gray-700">Responsable:</span> {{ $memorandum->author?->name }}</li>
                    <li><span class="font-semibold text-gray-700">Última actualización:</span> {{ optional($memorandum->updated_at)->diffForHumans() }}</li>
                </ul>
            </div>

            <div class="rounded-xl bg-white p-6 shadow">
                <h2 class="text-lg font-semibold text-gray-800">Historial reciente</h2>
                <div class="mt-4">
                    @include('company.memorandums.partials.status-history', ['history' => $memorandum->statusHistories->take(5)->sortBy('created_at')])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

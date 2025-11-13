@extends('layouts.company')

@section('content')
<div class="space-y-6 animate-fadeIn">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Nuevo memorándum</h1>
            <p class="text-sm text-gray-500">Registra la novedad, asigna al colaborador y establece el estado inicial.</p>
        </div>
        <a href="{{ route('company.memorandums.index') }}"
           class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">Volver al listado</a>
    </div>

    <form action="{{ route('company.memorandums.store') }}" method="POST" class="space-y-6 rounded-xl bg-white p-6 shadow">
        @csrf

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="subject" class="block text-sm font-semibold text-gray-700">Asunto *</label>
                <input id="subject" name="subject" type="text" value="{{ old('subject') }}"
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
                        <option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>
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
                <input id="issued_at" name="issued_at" type="date" value="{{ old('issued_at', now()->format('Y-m-d')) }}"
                       class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                @error('issued_at')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700">Estado inicial</label>
                <select id="status" name="status"
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                    @foreach ($statusOptions as $option)
                        <option value="{{ $option['value'] }}" @selected(old('status', 'draft') === $option['value'])>
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
                      class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]" required>{{ old('body') }}</textarea>
            @error('body')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="notes" class="block text-sm font-semibold text-gray-700">Notas de seguimiento</label>
            <textarea id="notes" name="notes" rows="3"
                      class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]"
                      placeholder="Describe brevemente las acciones a ejecutar o responsables adicionales.">{{ old('notes') }}</textarea>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('company.memorandums.index') }}"
               class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">Cancelar</a>
            <button type="submit"
                    class="rounded-lg bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-600 transition-colors">
                Guardar memorándum
            </button>
        </div>
    </form>
</div>
@endsection

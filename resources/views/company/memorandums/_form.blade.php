<div class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-gray-700">Asunto</label>
        <input type="text" name="subject" value="{{ old('subject', $memorandum->subject ?? '') }}" required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Cuerpo</label>
        <textarea name="body" rows="5" required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('body', $memorandum->body ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Empleado</label>
            <select name="employee_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Sin asignar</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" @selected(old('employee_id', $memorandum->employee_id ?? '') == $employee->id)>
                        {{ $employee->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Fecha de emisión</label>
            <input type="datetime-local" name="issued_at"
                   value="{{ old('issued_at', optional($memorandum->issued_at ?? null)?->format('Y-m-d\TH:i')) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Estado</label>
            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @foreach ($statusOptions as $option)
                    <option value="{{ $option['value'] }}" @selected(old('status', optional($memorandum->status)->value ?? '') === $option['value'])>
                        {{ $option['label'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Fecha de acuse</label>
            <input type="datetime-local" name="acknowledged_at"
                   value="{{ old('acknowledged_at', optional($memorandum->acknowledged_at ?? null)?->format('Y-m-d\TH:i')) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
    </div>
</div>

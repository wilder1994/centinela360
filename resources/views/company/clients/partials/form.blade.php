@php
    $isEdit = isset($client);
    $serviceTypes = old('service_types', $isEdit ? $client->services->pluck('service_type')->toArray() : ['Ronda']);
    $serviceSchedules = old('service_schedules', $isEdit ? $client->services->pluck('service_schedule')->toArray() : ['12H']);
    $serviceCount = old('service_count', count($serviceTypes));
@endphp

@if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg">
        <p class="font-semibold">Por favor corrige los siguientes errores:</p>
        <ul class="list-disc list-inside text-sm mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    <div>
        <label class="block text-sm font-medium text-gray-700">Razón social</label>
        <input type="text" name="business_name" value="{{ old('business_name', $client->business_name ?? '') }}"
               class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">NIT</label>
        <input type="text" name="nit" value="{{ old('nit', $client->nit ?? '') }}"
               class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Correo electrónico</label>
        <input type="email" name="email" value="{{ old('email', $client->email ?? '') }}"
               class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Dirección</label>
        <input type="text" name="address" value="{{ old('address', $client->address ?? '') }}"
               class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Barrio</label>
        <input type="text" name="neighborhood" value="{{ old('neighborhood', $client->neighborhood ?? '') }}"
               class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Ciudad</label>
        <input type="text" name="city" value="{{ old('city', $client->city ?? '') }}"
               class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Cantidad de servicios</label>
        <input type="number" name="service_count" id="service_count" value="{{ $serviceCount }}" min="1"
               class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Representante legal</label>
        <input type="text" name="representative_name" value="{{ old('representative_name', $client->representative_name ?? '') }}"
               class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Número de cuadrante</label>
        <input type="text" name="quadrant" value="{{ old('quadrant', $client->quadrant ?? '') }}"
               class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Fecha de inicio</label>
        <input type="date" name="start_date" value="{{ old('start_date', optional($client->start_date ?? null)?->format('Y-m-d')) }}"
               class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Fecha de terminación (opcional)</label>
        <input type="date" name="end_date" value="{{ old('end_date', optional($client->end_date ?? null)?->format('Y-m-d')) }}"
               class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
    </div>
</div>

<div class="mt-10">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Servicios</h3>
        <p class="text-sm text-gray-600">Ajusta los tipos y horarios según la cantidad de servicios.</p>
    </div>

    <div id="services-container" class="space-y-4" data-types='@json($serviceTypes)' data-schedules='@json($serviceSchedules)'>
        @foreach ($serviceTypes as $index => $type)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-white/60 rounded-lg p-4 shadow-sm border" data-service-row>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipo de servicio</label>
                    <select name="service_types[]" data-service-type
                            class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
                        @forelse ($serviceTypesOptions as $option)
                            <option value="{{ $option }}" @selected($option === $type)>{{ $option }}</option>
                        @empty
                            <option value="" disabled selected>Para seleccionar, Primero crea los tipos de servicio.</option>
                        @endforelse
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Horario</label>
                    <select name="service_schedules[]" data-service-schedule
                            class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
                        @for ($h = 1; $h <= 24; $h++)
                            <option value="{{ $h }}H" @selected(($serviceSchedules[$index] ?? '') === $h.'H')>
                                {{ $h }} {{ $h === 1 ? 'hora' : 'horas' }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="flex items-end justify-end">
                    <button type="button" data-remove-service
                            class="mt-2 text-sm text-red-600 hover:text-red-800 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                        Eliminar
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<template id="service-row-template">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-white/60 rounded-lg p-4 shadow-sm border" data-service-row>
        <div>
            <label class="block text-sm font-medium text-gray-700">Tipo de servicio</label>
            <select name="service_types[]" data-service-type
                    class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
                @forelse ($serviceTypesOptions as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @empty
                    <option value="" disabled selected>Para seleccionar, Primero crea los tipos de servicio.</option>
                @endforelse
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Horario</label>
            <select name="service_schedules[]" data-service-schedule
                    class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
                @for ($h = 1; $h <= 24; $h++)
                    <option value="{{ $h }}H">{{ $h }} {{ $h === 1 ? 'hora' : 'horas' }}</option>
                @endfor
            </select>
        </div>

        <div class="flex items-end justify-end">
            <button type="button" data-remove-service
                    class="mt-2 text-sm text-red-600 hover:text-red-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
                Eliminar
            </button>
        </div>
    </div>
</template>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('services-container');
        const countInput = document.getElementById('service_count');
        const template = document.getElementById('service-row-template');

        const initialTypes = JSON.parse(container.dataset.types || '[]');
        const initialSchedules = JSON.parse(container.dataset.schedules || '[]');

        function syncCount() {
            countInput.value = container.children.length;
        }

        function createRow(type = '', schedule = '') {
            const clone = template.content.cloneNode(true);
            const row = clone.querySelector('[data-service-row]');
            const typeSelect = row.querySelector('[data-service-type]');
            const scheduleSelect = row.querySelector('[data-service-schedule]');
            const removeBtn = row.querySelector('[data-remove-service]');

            if (type) typeSelect.value = type;
            if (schedule) scheduleSelect.value = schedule;

            removeBtn.addEventListener('click', (event) => {
                event.preventDefault();
                if (container.children.length > 1) {
                    row.remove();
                    syncCount();
                }
            });

            return row;
        }

        function adjustRows() {
            let desired = parseInt(countInput.value, 10);
            if (!desired || desired < 1) desired = 1;

            while (container.children.length < desired) {
                container.appendChild(createRow());
            }

            while (container.children.length > desired) {
                container.removeChild(container.lastElementChild);
            }

            syncCount();
        }

        // Cargar valores iniciales
        container.innerHTML = '';
        const maxLength = Math.max(initialTypes.length, initialSchedules.length);
        const total = maxLength || 1;

        for (let i = 0; i < total; i++) {
            container.appendChild(createRow(initialTypes[i] || '', initialSchedules[i] || ''));
        }
        syncCount();

        countInput.addEventListener('change', adjustRows);
    });
</script>
@endpush

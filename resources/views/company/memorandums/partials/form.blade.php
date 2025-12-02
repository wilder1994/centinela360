@php
    $memo = $memorandum ?? null;
    $selectedAssigned = old('assigned_to', $memo->assigned_to ?? null);

    $employeesData = collect($employees ?? [])->map(function ($e) use ($clientes) {
        $clientName = '';
        if (isset($e->client_id) && $clientes) {
            $client = collect($clientes)->firstWhere('id', $e->client_id);
            $clientName = $client->name
                ?? $client->business_name
                ?? $client->razon_social
                ?? $client->nombre
                ?? '';
        }
        return [
            'full_name' => trim(($e->first_name ?? '') . ' ' . ($e->last_name ?? '')),
            'document_number' => $e->document_number,
            'position' => $e->position,
            'client_name' => $clientName,
        ];
    });
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
    {{-- Fila 1: Nombre / Cédula / Cargo --}}
    <div class="space-y-1">
        <label class="block text-sm font-semibold text-gray-700">Nombre</label>
        <input
            type="text"
            name="name"
            list="employees-list"
            value="{{ old('name', $memo->name ?? '') }}"
            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-[var(--primary)] focus:ring-[var(--primary)]"
            placeholder="Escribe para buscar empleado"
            autocomplete="off"
            spellcheck="true"
            autocapitalize="sentences"
            autocorrect="on"
            required
        >
        <datalist id="employees-list">
            @foreach(($employees ?? collect()) as $employee)
                @php
                    $fullName = trim(($employee->first_name ?? '') . ' ' . ($employee->last_name ?? ''));
                @endphp
                <option value="{{ $fullName }}"></option>
            @endforeach
        </datalist>
        @error('name')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-semibold text-gray-700">Cédula</label>
        <input
            type="text"
            name="cedula"
            value="{{ old('cedula', $memo->cedula ?? '') }}"
            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-[var(--primary)] focus:ring-[var(--primary)]"
            spellcheck="true"
            autocapitalize="sentences"
            autocorrect="on"
            required
        >
        @error('cedula')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-semibold text-gray-700">Cargo</label>
        <input
            type="text"
            name="cargo"
            value="{{ old('cargo', $memo->cargo ?? '') }}"
            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-[var(--primary)] focus:ring-[var(--primary)]"
            spellcheck="true"
            autocapitalize="sentences"
            autocorrect="on"
            required
        >
        @error('cargo')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Fila 2: Puesto / Asunto / Responsable --}}
    <div class="space-y-1">
        <label class="block text-sm font-semibold text-gray-700">Puesto</label>
        <input
            type="text"
            name="puesto"
            value="{{ old('puesto', $memo->puesto ?? '') }}"
            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-[var(--primary)] focus:ring-[var(--primary)]"
            spellcheck="true"
            autocapitalize="sentences"
            autocorrect="on"
            required
        >
        @error('puesto')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-semibold text-gray-700">Asunto</label>
        <input
            type="text"
            name="title"
            value="{{ old('title', $memo->title ?? '') }}"
            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-[var(--primary)] focus:ring-[var(--primary)]"
            spellcheck="true"
            autocapitalize="sentences"
            autocorrect="on"
            required
        >
        @error('title')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-semibold text-gray-700">Responsable</label>
        <select
            name="assigned_to"
            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm bg-white focus:border-[var(--primary)] focus:ring-[var(--primary)]"
        >
            <option value="">Seleccionar responsable</option>
            @foreach ($users as $userItem)
                <option value="{{ $userItem->id }}" @selected($selectedAssigned == $userItem->id)>
                    {{ $userItem->name ?? ($userItem->nombre ?? 'Usuario') }}
                </option>
            @endforeach
        </select>
        @error('assigned_to')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Fila 3: Descripción --}}
    <div class="md:col-span-3 space-y-1">
        <label class="block text-sm font-semibold text-gray-700">Descripción</label>
        <textarea
            name="body"
            rows="4"
            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-[var(--primary)] focus:ring-[var(--primary)]"
            spellcheck="true"
            autocapitalize="sentences"
            autocorrect="on"
            required
        >{{ old('body', $memo->body ?? '') }}</textarea>
        @error('body')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Fila 4: Prioridad --}}
    <div class="space-y-1">
        <label class="block text-sm font-semibold text-gray-700">Prioridad</label>
        <select
            name="prioridad"
            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm bg-white focus:border-[var(--primary)] focus:ring-[var(--primary)]"
            required
        >
            <option value="">Seleccionar prioridad</option>
            @foreach (['alta' => 'Alta', 'media' => 'Media', 'baja' => 'Baja'] as $value => $label)
                <option value="{{ $value }}" @selected(old('prioridad', $memo->prioridad ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('prioridad')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

<script>
    (function() {
        const employees = @json($employeesData);
        document.addEventListener('DOMContentLoaded', () => {
            const nameInput = document.querySelector('input[name="name"]');
            const cedulaInput = document.querySelector('input[name="cedula"]');
            const cargoInput = document.querySelector('input[name="cargo"]');
            const puestoInput = document.querySelector('input[name="puesto"]');

            if (!nameInput) return;

            function fillFields(fullName) {
                const emp = employees.find(e => (e.full_name || '').toLowerCase() === fullName.toLowerCase());
                if (!emp) return;
                if (cedulaInput) cedulaInput.value = emp.document_number || '';
                if (cargoInput) cargoInput.value = emp.position || '';
                if (puestoInput && emp.client_name) puestoInput.value = emp.client_name;
            }

            nameInput.addEventListener('change', (e) => fillFields(e.target.value));
            nameInput.addEventListener('blur', (e) => fillFields(e.target.value));
        });
    })();
</script>

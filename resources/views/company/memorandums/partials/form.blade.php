@php
    $memo = $memorandum ?? null;
    $selectedAssigned = old('assigned_to', $memo->assigned_to ?? null);
@endphp

<div class="p-4 bg-white rounded-xl shadow">
    <h3 class="font-semibold mb-3">MEMORANDO</h3>

    {{-- Fila 1: Puesto (cliente) / Asunto / Cargo --}}
    <div class="flex flex-col md:flex-row gap-4">
        {{-- Puesto (cliente) --}}
        <div class="mb-2 flex-grow" style="flex-grow: 4;">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Puesto</label>
            <select
                name="puesto"
                class="border rounded px-3 py-2 w-full text-sm bg-white"
                required
            >
                <option value="">Seleccionar cliente / puesto</option>
                @foreach ($clientes as $cliente)
                    @php
                        $valor = $cliente->name
                            ?? $cliente->business_name
                            ?? $cliente->razon_social
                            ?? $cliente->nombre
                            ?? '';
                    @endphp
                    <option
                        value="{{ $valor }}"
                        @selected(old('puesto', $memo->puesto ?? '') === $valor)
                    >
                        {{ $valor }}
                    </option>
                @endforeach
            </select>
            @error('puesto')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Asunto (usa el campo title) --}}
        <div class="mb-2 flex-grow" style="flex-grow: 2;">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Asunto</label>
            <input
                type="text"
                name="title"
                value="{{ old('title', $memo->title ?? '') }}"
                class="border rounded px-3 py-2 w-full text-sm"
                placeholder="Asunto del memorando"
                required
            >
            @error('title')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Cargo del empleado --}}
        <div class="mb-2 flex-grow" style="flex-grow: 2;">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Cargo</label>
            <input
                type="text"
                name="cargo"
                value="{{ old('cargo', $memo->cargo ?? '') }}"
                class="border rounded px-3 py-2 w-full text-sm"
                placeholder="Cargo del empleado"
            >
            @error('cargo')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Fila 2: Nombre / Cédula / Responsable --}}
    <div class="flex flex-col md:flex-row gap-4">
        {{-- Nombre del empleado --}}
        <div class="mb-2 flex-grow" style="flex-grow: 4;">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre</label>
            <input
                type="text"
                name="nombre"
                value="{{ old('nombre', $memo->nombre ?? '') }}"
                class="border rounded px-3 py-2 w-full text-sm"
                placeholder="Nombre del empleado"
            >
            @error('nombre')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Cédula --}}
        <div class="mb-2 flex-grow" style="flex-grow: 2;">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Cédula</label>
            <input
                type="text"
                name="cedula"
                value="{{ old('cedula', $memo->cedula ?? '') }}"
                class="border rounded px-3 py-2 w-full text-sm"
                placeholder="Documento del empleado"
            >
            @error('cedula')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Responsable (Asignado a) --}}
        <div class="mb-2 flex-grow" style="flex-grow: 2;">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Responsable</label>
            <select
                name="assigned_to"
                class="border rounded px-3 py-2 w-full text-sm bg-white"
            >
                <option value="">Seleccionar responsable</option>
                @foreach ($responsables as $responsable)
                    <option
                        value="{{ $responsable->id }}"
                        @selected($selectedAssigned == $responsable->id)
                    >
                        {{ $responsable->name }}
                    </option>
                @endforeach
            </select>
            @error('assigned_to')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Descripción --}}
    <div class="mb-3">
        <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción</label>
        <textarea
            name="body"
            rows="3"
            class="border rounded px-3 py-2 w-full text-sm"
            placeholder="Descripción breve del memorando"
        >{{ old('body', $memo->body ?? '') }}</textarea>
        @error('body')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Prioridad (como en P3-Global, debajo de la descripción) --}}
    <div class="mb-1">
        <label class="block text-sm font-semibold text-gray-700 mb-1">Prioridad</label>
        <select
            name="prioridad"
            class="border rounded px-3 py-2 w-full text-sm bg-white"
        >
            @foreach ($prioridadOptions as $option)
                <option
                    value="{{ $option }}"
                    @selected(old('prioridad', $memo->prioridad ?? 'media') === $option)
                >
                    {{ ucfirst($option) }}
                </option>
            @endforeach
        </select>
        @error('prioridad')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

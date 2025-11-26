<div class="p-4 sm:p-6 bg-white rounded-xl shadow">
    <h3 class="font-semibold mb-3 text-gray-800 uppercase text-sm">
        MEMORANDO
    </h3>

    {{-- Mensaje flash simple --}}
    @if (session('status'))
        <div class="mb-3 px-3 py-2 rounded bg-emerald-50 text-emerald-700 text-sm border border-emerald-100">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-4">
        {{-- Fila 1: Puesto / Asunto / Cargo --}}
        <div class="flex flex-col md:flex-row gap-4">
            {{-- Puesto --}}
            <div class="flex-1 relative" wire:click.away="hideClientResults">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Puesto (cliente)</label>
                <input
                    type="search"
                    wire:model.live.debounce.300ms="puesto"
                    wire:focus="openClientResults"
                    wire:keydown.escape="hideClientResults"
                    class="border rounded px-3 py-2 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)]"
                    placeholder="Escribe para buscar y seleccionar un cliente"
                    autocomplete="off"
                />
                <input type="hidden" wire:model="clientId" />
                @if($clients->isNotEmpty() && $showClientResults)
                    <div class="absolute z-10 mt-1 w-full bg-white border rounded shadow max-h-52 overflow-auto">
                        @foreach($clients as $client)
                            <button type="button" wire:click="selectClient({{ $client->id }})" class="block w-full text-left px-3 py-2 hover:bg-gray-100 text-sm">
                                {{ $client->business_name }}
                            </button>
                        @endforeach
                    </div>
                @endif
                <p class="text-xs text-gray-500 mt-1">Solo se permite elegir un cliente existente, no texto libre.</p>
                @error('clientId')
                    <div class="text-red-600 text-xs mt-1">Debes seleccionar un puesto válido</div>
                @enderror
                @error('puesto')
                    <div class="text-red-600 text-xs mt-1">El puesto es obligatorio</div>
                @enderror
            </div>

            {{-- Asunto --}}
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Asunto</label>
                <input
                    type="text"
                    wire:model.defer="subject"
                    class="border rounded px-3 py-2 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)]"
                    placeholder="Asunto"
                />
                @error('subject')
                    <div class="text-red-600 text-xs mt-1">El asunto es obligatorio</div>
                @enderror
            </div>

            {{-- Cargo --}}
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Cargo</label>
                <input
                    type="text"
                    wire:model.defer="cargo"
                    class="border rounded px-3 py-2 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)]"
                    placeholder="Cargo"
                    readonly
                />
                <p class="text-xs text-gray-500 mt-1">Se llena automáticamente con el tipo/posición del empleado.</p>
                @error('cargo')
                    <div class="text-red-600 text-xs mt-1">El cargo es obligatorio</div>
                @enderror
            </div>
        </div>

        {{-- Fila 2: Nombre / Cédula / Responsable --}}
        <div class="flex flex-col md:flex-row gap-4">
            {{-- Nombre --}}
            <div class="flex-1 relative" wire:click.away="hideEmployeeResults">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nombre</label>
                <input
                    type="search"
                    wire:model.live.debounce.300ms="nombre"
                    wire:focus="openEmployeeResults"
                    wire:keydown.escape="hideEmployeeResults"
                    class="border rounded px-3 py-2 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)]"
                    placeholder="Escribe para buscar un empleado del cliente seleccionado"
                    autocomplete="off"
                    @disabled(!$clientId)
                />
                <input type="hidden" wire:model="employeeId" />
                @if($clientId && $employees->isNotEmpty() && $showEmployeeResults)
                    <div class="absolute z-10 mt-1 w-full bg-white border rounded shadow max-h-52 overflow-auto">
                        @foreach($employees as $employee)
                            <button type="button" wire:click="selectEmployee({{ $employee->id }})" class="block w-full text-left px-3 py-2 hover:bg-gray-100 text-sm">
                                {{ $employee->full_name }}
                                @if($employee->position)
                                    <span class="text-gray-500 text-xs"> · {{ $employee->position }}</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                @endif
                <p class="text-xs text-gray-500 mt-1">
                    Primero elige un cliente; luego podrás buscar solo entre sus empleados.
                </p>
                @error('employeeId')
                    <div class="text-red-600 text-xs mt-1">Selecciona un empleado válido</div>
                @enderror
                @error('nombre')
                    <div class="text-red-600 text-xs mt-1">El nombre es obligatorio</div>
                @enderror
            </div>

            {{-- Cédula --}}
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Cédula</label>
                <input
                    type="text"
                    wire:model.defer="cedula"
                    class="border rounded px-3 py-2 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)]"
                    placeholder="Cédula"
                    readonly
                />
                <p class="text-xs text-gray-500 mt-1">Se llena automáticamente al seleccionar el empleado.</p>
                @error('cedula')
                    <div class="text-red-600 text-xs mt-1">La cédula es obligatoria</div>
                @enderror
            </div>

            {{-- Responsable --}}
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Seleccionar responsable</label>
                <select
                    wire:model.defer="responsable"
                    class="border rounded px-3 py-2 w-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)]"
                >
                    @if($usuarios->isEmpty())
                        <option value="" disabled>No hay responsables disponibles</option>
                    @else
                        <option value="">Seleccionar responsable</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                        @endforeach
                    @endif
                </select>
                @error('responsable')
                    <div class="text-red-600 text-xs mt-1">Debe asignar un responsable</div>
                @enderror
            </div>
        </div>

        {{-- Descripción --}}
        <div>
            <textarea
                wire:model.defer="descripcion"
                rows="4"
                class="border rounded px-3 py-2 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)]"
                placeholder="Descripción"
            ></textarea>
            @error('descripcion')
                <div class="text-red-600 text-xs mt-1">La descripción es obligatoria</div>
            @enderror
        </div>

        {{-- Prioridad --}}
        <div>
            <select
                wire:model.defer="prioridad"
                class="border rounded px-3 py-2 w-full text-sm focus:outline-none focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)]"
            >
                <option value="">Selecciona prioridad</option>
                <option value="urgente">Urgente</option>
                <option value="alta">Alta</option>
                <option value="media">Media</option>
                <option value="baja">Baja</option>
            </select>
            @error('prioridad')
                <div class="text-red-600 text-xs mt-1">Debes seleccionar una prioridad</div>
            @enderror
        </div>

        {{-- Botón --}}
        <div class="pt-2">
            <button
                type="submit"
                class="px-4 py-2 rounded bg-gray-800 text-white text-sm font-semibold hover:bg-gray-900 transition"
                wire:loading.attr="disabled"
            >
                {{ $isEdit ? 'Guardar cambios' : 'Crear' }}
            </button>
        </div>
    </form>
</div>

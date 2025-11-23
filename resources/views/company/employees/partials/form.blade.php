@php
    $employee ??= null;
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data"
      class="bg-white p-8 rounded-xl shadow-2xl space-y-8 mt-6 border border-gray-200 max-w-6xl w-full mx-auto">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="flex flex-col items-center gap-3">
        <div class="relative">
            <img id="employee-photo-preview"
                src="{{ $employee?->photo_url ?? asset('images/default-avatar.png') }}"
                class="w-24 h-24 rounded-full border-4 border-white shadow-xl object-cover"
                alt="Imagen de perfil">
        </div>

        <label class="inline-flex items-center px-4 py-2 rounded-lg bg-[var(--primary)] text-white text-sm font-medium cursor-pointer hover:bg-[var(--primary)]/90">
            <span>Subir / tomar foto</span>
            <input
                id="employee-photo-input"
                type="file"
                name="photo"
                accept="image/*"
                capture="environment"
                class="hidden">
        </label>

        @error('photo')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700">Nombres completos</label>
            <input name="first_name" type="text" value="{{ old('first_name', $employee->first_name ?? '') }}" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Nombres completos" required>
            @error('first_name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Apellidos</label>
            <input name="last_name" type="text" value="{{ old('last_name', $employee->last_name ?? '') }}" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Apellidos" required>
            @error('last_name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tipo empleado</label>
            <select name="position" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
                <option value="">Selecciona</option>
                @foreach($positions as $position)
                    <option value="{{ $position }}" @selected(old('position', $employee->position ?? '') === $position)>{{ $position }}</option>
                @endforeach
            </select>
            @error('position') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tipo documento</label>
            <select name="document_type" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
                <option value="">Selecciona</option>
                @foreach($documentTypes as $type)
                    <option value="{{ $type }}" @selected(old('document_type', $employee->document_type ?? '') === $type)>{{ $type }}</option>
                @endforeach
            </select>
            @error('document_type') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Número documento</label>
            <input name="document_number" type="text" value="{{ old('document_number', $employee->document_number ?? '') }}" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Número documento" required>
            @error('document_number') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">RH</label>
            <select name="rh" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
                <option value="">Selecciona</option>
                @foreach($rhOptions as $rh)
                    <option value="{{ $rh }}" @selected(old('rh', $employee->rh ?? '') === $rh)>{{ $rh }}</option>
                @endforeach
            </select>
            @error('rh') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700">Teléfono</label>
            <input name="phone" type="text" value="{{ old('phone', $employee->phone ?? '') }}" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Teléfono" required>
            @error('phone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Correo (opcional)</label>
            <input name="email" type="email" value="{{ old('email', $employee->email ?? '') }}" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="correo@ejemplo.com">
            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Dirección</label>
            <input name="address" type="text" value="{{ old('address', $employee->address ?? '') }}" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Dirección" required>
            @error('address') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Fecha nacimiento</label>
           <input name="birth_date" type="date"
                value="{{ old('birth_date', $employee?->birth_date?->format('Y-m-d') ?? '') }}"
                class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
            @error('birth_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Fecha ingreso</label>
            <input name="start_date" type="date"
                value="{{ old('start_date', $employee?->start_date?->format('Y-m-d') ?? '') }}"
                class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
            @error('start_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Fecha vencimiento carnet</label>
            <input name="badge_expires_at" type="date"
                value="{{ old('badge_expires_at', $employee?->badge_expires_at?->format('Y-m-d') ?? '') }}"
                class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
            @error('badge_expires_at') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Puesto (Cliente asignado)</label>
            <div class="mt-2 space-y-3">
                <input type="text" placeholder="Buscar cliente..." class="input w-full rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" data-client-search>
                <select name="client_id" id="client_id" class="block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" data-client-select required>
                    <option value="">Selecciona un cliente</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" @selected(old('client_id', $employee->client_id ?? '') == $client->id)>{{ $client->business_name }} — {{ $client->city }}</option>
                    @endforeach
                </select>
            </div>
            @error('client_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tipo de servicio asignado</label>
            <select name="service_type" id="service_type" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" data-service-select required>
                <option value="">Selecciona el servicio</option>
                @foreach($serviceTypesOptions as $type)
                    <option value="{{ $type }}" @selected(old('service_type', $employee->service_type ?? '') === $type)>{{ $type }}</option>
                @endforeach
            </select>
            @error('service_type') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700">Estado</label>
            <select name="status" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
                @foreach($statusOptions as $status)
                    <option value="{{ $status }}" @selected(old('status', $employee->status ?? 'Activo') === $status)>{{ $status }}</option>
                @endforeach
            </select>
            @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Contacto emergencia (nombre)</label>
            <input name="emergency_contact_name" type="text" value="{{ old('emergency_contact_name', $employee->emergency_contact_name ?? '') }}" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Nombre de contacto emergencia" required>
            @error('emergency_contact_name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Teléfono emergencia</label>
            <input name="emergency_contact_phone" type="text" value="{{ old('emergency_contact_phone', $employee->emergency_contact_phone ?? '') }}" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Teléfono de emergencia" required>
            @error('emergency_contact_phone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Observaciones</label>
        <textarea name="notes" class="mt-2 block w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" rows="4" placeholder="Observaciones">{{ old('notes', $employee->notes ?? '') }}</textarea>
        @error('notes') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center justify-end gap-4 mt-4">
        <button type="submit" class="bg-[var(--primary)] text-white px-6 py-3 rounded-lg shadow-md hover:bg-[var(--primary)]/90 transition-all">{{ $buttonLabel }}</button>
        <a href="{{ route('company.employees.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">Cancelar</a>
    </div>
</form>

@push('scripts')
    @once
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Preview de la foto del empleado
                const photoInput = document.getElementById('employee-photo-input');
                const photoPreview = document.getElementById('employee-photo-preview');

                if (photoInput && photoPreview) {
                    photoInput.addEventListener('change', (event) => {
                        const [file] = event.target.files;
                        if (file) {
                            photoPreview.src = URL.createObjectURL(file);
                        }
                    });
                }

                const clientSelect = document.querySelector('[data-client-select]');
                const clientSearch = document.querySelector('[data-client-search]');
                const serviceSelect = document.querySelector('[data-service-select]');
                const serviceTypes = @json($serviceTypesOptions);
                const clientServiceMap = @json($clientServicesMap);
                const initialService = @json(old('service_type', $employee->service_type ?? ''));

                const fillServiceOptions = (clientId) => {
                    const options = clientServiceMap[clientId] && clientServiceMap[clientId].length
                        ? clientServiceMap[clientId]
                        : serviceTypes;

                    const currentSelection = serviceSelect.value || initialService;
                    serviceSelect.innerHTML = '<option value="">Selecciona el servicio</option>';
                    options.forEach(type => {
                        const option = document.createElement('option');
                        option.value = type;
                        option.textContent = type;
                        if (type === currentSelection) {
                            option.selected = true;
                        }
                        serviceSelect.appendChild(option);
                    });
                };

                if (clientSelect && serviceSelect) {
                    fillServiceOptions(clientSelect.value || null);
                    clientSelect.addEventListener('change', (event) => {
                        fillServiceOptions(event.target.value || null);
                    });
                }

                if (clientSelect && clientSearch) {
                    clientSearch.addEventListener('input', (event) => {
                        const term = event.target.value.toLowerCase();
                        let firstVisible = null;

                        Array.from(clientSelect.options).forEach(option => {
                            if (!option.value) {
                                option.hidden = false;
                                return;
                            }

                            const match = option.textContent.toLowerCase().includes(term);
                            option.hidden = !match;
                            if (match && !firstVisible) {
                                firstVisible = option.value;
                            }
                        });

                        if (term && firstVisible) {
                            clientSelect.value = firstVisible;
                            clientSelect.dispatchEvent(new Event('change'));
                        }
                    });
                }
            });
        </script>
    @endonce
@endpush

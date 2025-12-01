<div class="w-full px-4 sm:px-6 lg:px-10 xl:px-16 py-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Crear usuario</h1>
            <p class="text-sm text-gray-500">Registra un nuevo usuario y asigna un rol.</p>
        </div>
        <a href="{{ route('company.users.index') }}" class="text-[var(--primary)] text-sm font-semibold hover:underline">
            Volver al listado
        </a>
    </div>

    <div class="bg-white rounded-xl shadow p-6 space-y-4">
        <form wire:submit.prevent="save" class="space-y-4">
            <div class="grid sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-gray-700">Nombre</label>
                    <input type="text" wire:model.defer="name"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                    @error('name') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-gray-700">Correo electrónico</label>
                    <input type="email" wire:model.defer="email"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                    @error('email') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-gray-700">Contraseña</label>
                    <input type="password" wire:model.defer="password"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                    @error('password') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-gray-700">Confirmar contraseña</label>
                    <input type="password" wire:model.defer="password_confirmation"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-sm font-semibold text-gray-700">Rol</label>
                <select wire:model.defer="role"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm bg-white focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                    <option value="">Seleccionar rol</option>
                    @foreach ($roles as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('role') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <a href="{{ route('company.users.index') }}" class="text-sm text-gray-600 hover:underline">Cancelar</a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 rounded-lg bg-[var(--primary)] text-white text-sm font-semibold hover:opacity-90 transition">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

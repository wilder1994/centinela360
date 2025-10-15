@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8 border border-gray-100">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Editar usuario</h2>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nombre -->
            <div>
                <label class="block text-gray-700 text-sm font-medium">Nombre completo</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="mt-1 w-full border-gray-300 rounded-lg focus:ring-cyan-500 focus:border-cyan-500">
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Correo -->
            <div>
                <label class="block text-gray-700 text-sm font-medium">Correo electrónico</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="mt-1 w-full border-gray-300 rounded-lg focus:ring-cyan-500 focus:border-cyan-500">
                @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Contraseña -->
            <div>
                <label class="block text-gray-700 text-sm font-medium">Nueva contraseña (opcional)</label>
                <input type="password" name="password"
                    class="mt-1 w-full border-gray-300 rounded-lg focus:ring-cyan-500 focus:border-cyan-500">
                @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Confirmar -->
            <div>
                <label class="block text-gray-700 text-sm font-medium">Confirmar contraseña</label>
                <input type="password" name="password_confirmation"
                    class="mt-1 w-full border-gray-300 rounded-lg focus:ring-cyan-500 focus:border-cyan-500">
            </div>

            <!-- Teléfono -->
            <div>
                <label class="block text-gray-700 text-sm font-medium">Teléfono</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="mt-1 w-full border-gray-300 rounded-lg focus:ring-cyan-500 focus:border-cyan-500">
            </div>

            <!-- Empresa -->
            <div>
                <label class="block text-gray-700 text-sm font-medium">Empresa</label>
                <select name="company_id" class="mt-1 w-full border-gray-300 rounded-lg focus:ring-cyan-500 focus:border-cyan-500">
                    <option value="">Sin empresa</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}" {{ $user->company_id == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Roles -->
            <div class="md:col-span-2">
                <label class="block text-gray-700 text-sm font-medium mb-2">Roles asignados</label>
                <div class="flex flex-wrap gap-3">
                    @foreach ($roles as $role)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                {{ in_array($role->id, $userRoles) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                            <span class="text-gray-700 text-sm">{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Estado -->
            <div>
                <label class="flex items-center mt-4 space-x-2">
                    <input type="checkbox" name="active" {{ $user->active ? 'checked' : '' }}
                        class="rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                    <span class="text-gray-700 text-sm">Usuario activo</span>
                </label>
            </div>

            <!-- Foto de perfil -->
            <div class="md:col-span-2">
                <label class="block text-gray-700 text-sm font-medium mb-2">Foto de perfil</label>

                @if ($user->photo)
                    <div class="mb-3">
                        <img src="{{ asset('storage/'.$user->photo) }}" alt="Foto actual"
                             class="w-24 h-24 rounded-full object-cover border border-gray-300">
                    </div>
                @endif

                <input type="file" name="photo" accept="image/*" onchange="previewPhoto(event)"
                    class="block w-full text-sm text-gray-700 border-gray-300 rounded-lg cursor-pointer focus:ring-cyan-500 focus:border-cyan-500">
                <img id="photoPreview" class="mt-4 w-24 h-24 rounded-full object-cover hidden">
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900">Cancelar</a>
            <button type="submit" class="ml-4 px-6 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition">
                Actualizar usuario
            </button>
        </div>
    </form>
</div>

<script>
function previewPhoto(event) {
    const output = document.getElementById('photoPreview');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.classList.remove('hidden');
}
</script>
@endsection

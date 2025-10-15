@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Encabezado -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Gestión de Usuarios</h1>
        <a href="{{ route('admin.users.create') }}" 
           class="bg-cyan-600 hover:bg-cyan-700 text-white font-semibold px-4 py-2 rounded-lg shadow transition">
            + Nuevo Usuario
        </a>
    </div>

    <!-- Mensaje de éxito -->
    @if (session('success'))
        <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabla de usuarios -->
    <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-100">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
                <tr>
                    <th class="px-6 py-3 text-left">Foto</th>
                    <th class="px-6 py-3 text-left">Nombre</th>
                    <th class="px-6 py-3 text-left">Correo</th>
                    <th class="px-6 py-3 text-left">Empresa</th>
                    <th class="px-6 py-3 text-left">Rol(es)</th>
                    <th class="px-6 py-3 text-center">Estado</th>
                    <th class="px-6 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-600">
                @forelse ($users as $user)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            @if($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto de perfil" class="h-10 w-10 rounded-full">
                            @else
                                <span class="text-gray-400 italic">Sin logo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            {{ $user->company ? $user->company->name : '—' }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($user->roles->isNotEmpty())
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($user->roles as $role)
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-md">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">Sin rol</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="active" onchange="this.form.submit()" {{ $user->active ? 'checked' : '' }} class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer 
                                        peer-checked:after:translate-x-full peer-checked:after:border-white 
                                        after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white 
                                        after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all 
                                        peer-checked:bg-green-500"></div>
                                </label>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.users.edit', $user) }}" 
                               class="text-blue-600 hover:text-blue-800 font-medium">Editar</a>

                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 font-medium"
                                        onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-400">
                            No hay usuarios registrados aún.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

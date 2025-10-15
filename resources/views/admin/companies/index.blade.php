@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Título y botón principal -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Empresas registradas</h1>
        <a href="{{ route('admin.companies.create') }}"
           class="bg-cyan-600 text-white px-5 py-2 rounded-lg hover:bg-cyan-700 transition">
           + Nueva Empresa
        </a>
    </div>

    <!-- Mensaje de éxito -->
    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg shadow">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabla de empresas -->
    <div class="bg-white shadow rounded-xl border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-600 uppercase text-sm font-semibold">
                    <th class="px-6 py-3">Logo</th>
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">NIT</th>
                    <th class="px-6 py-3">Correo</th>
                    <th class="px-6 py-3">Teléfono</th>
                    <th class="px-6 py-3 text-center">Estado</th> <!-- Aquí ajustamos la celda de Estado -->
                    <th class="px-6 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($companies as $company)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            @if($company->logo)
                                <img src="{{ asset('storage/' . $company->logo) }}" class="h-10 w-10 rounded-full object-cover">
                            @else
                                <span class="text-gray-400 italic">Sin logo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $company->name }}</td>
                        <td class="px-6 py-4">{{ $company->nit }}</td>
                        <td class="px-6 py-4">{{ $company->email }}</td>
                        <td class="px-6 py-4">{{ $company->phone ?? '—' }}</td>
                        <td class="px-6 py-4 text-center"> <!-- Centramos solo esta columna -->
                            <form action="{{ route('admin.companies.toggle', $company->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="active" onchange="this.form.submit()"
                                        class="sr-only peer" {{ $company->active ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-cyan-300
                                                peer-checked:after:translate-x-full peer-checked:after:border-white after:content-['']
                                                after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300
                                                after:border after:rounded-full after:h-5 after:w-5 after:transition-all
                                                peer-checked:bg-green-500"></div>
                                </label>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.companies.edit', $company) }}"
                               class="text-blue-600 hover:text-blue-800 font-medium">Editar</a>

                            <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" class="inline"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar esta empresa?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 py-6">
                            No hay empresas registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-6">
        {{ $companies->links() }}
    </div>

</div>
@endsection

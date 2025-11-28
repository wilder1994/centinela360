@extends('layouts.company')

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Editar memorándum</h2>
                <p class="text-sm text-gray-500">Actualiza los detalles del memorándum.</p>
            </div>
            <a href="{{ route('company.memorandums.show', $memorandum) }}" class="text-sm text-blue-600 hover:underline">Volver</a>
        </div>

        <form action="{{ route('company.memorandums.update', $memorandum) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            @include('company.memorandums._form')

            <div class="flex justify-end gap-3">
                <a href="{{ route('company.memorandums.show', $memorandum) }}" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded">Cancelar</a>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded shadow hover:bg-blue-700">Guardar cambios</button>
            </div>
        </form>

        <form action="{{ route('company.memorandums.destroy', $memorandum) }}" method="POST"
              onsubmit="return confirm('¿Seguro que deseas eliminar este memorándum?');" class="mt-4">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm text-red-600 hover:underline">Eliminar memorándum</button>
        </form>
    </div>
@endsection

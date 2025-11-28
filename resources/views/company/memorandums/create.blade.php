@extends('layouts.company')

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Nuevo memorándum</h2>
                <p class="text-sm text-gray-500">Registra un memorándum desde cero.</p>
            </div>
            <a href="{{ route('company.memorandums.index') }}" class="text-sm text-blue-600 hover:underline">Volver al listado</a>
        </div>

        <form action="{{ route('company.memorandums.store') }}" method="POST" class="space-y-6">
            @csrf
            @include('company.memorandums._form', ['memorandum' => new \App\Models\Memorandum()])

            <div class="flex justify-end gap-3">
                <a href="{{ route('company.memorandums.index') }}" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded">Cancelar</a>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded shadow hover:bg-blue-700">Guardar</button>
            </div>
        </form>
    </div>
@endsection

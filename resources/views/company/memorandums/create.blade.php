@extends('layouts.company')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Nuevo memorando</h1>
        <p class="text-sm text-gray-500">Crea un memorando y asignalo a un responsable.</p>
    </div>

    <div class="bg-white rounded-xl shadow p-6 space-y-4">
        <form method="POST" action="{{ route('company.memorandums.store') }}" class="space-y-6">
            @csrf

            @include('company.memorandums.partials.form')

            <div class="flex items-center gap-3">
                <a href="{{ route('company.memorandums.index') }}" class="text-gray-600 hover:underline">Cancelar</a>
                <button type="submit" class="bg-[var(--primary)] text-white px-4 py-2 rounded-lg">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

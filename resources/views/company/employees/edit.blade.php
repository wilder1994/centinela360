@extends('layouts.company')

@section('content')
<div class="animate-fadeIn space-y-6 max-w-7xl mx-auto px-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-gray-800">Editar empleado</h1>
            <p class="text-sm text-gray-500">Actualiza la información y asignación del empleado.</p>
        </div>
        <a href="{{ route('company.employees.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Volver</a>
    </div>

    @include('company.employees.partials.form', [
        'action' => route('company.employees.update', $employee),
        'method' => 'PUT',
        'buttonLabel' => 'Actualizar',
        'employee' => $employee,
    ])
</div>
@endsection

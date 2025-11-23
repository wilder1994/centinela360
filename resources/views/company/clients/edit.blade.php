@extends('layouts.company')

@section('content')
<div class="animate-fadeIn space-y-8 max-w-6xl mx-auto px-6 relative">
    <!-- Fondo con degradado -->
    <div class="absolute inset-0 -z-10 bg-gradient-to-b from-[var(--primary)] to-[var(--secondary)] opacity-80"></div>

    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-semibold text-gray-800">Editar cliente</h1>
        <a href="{{ route('company.clients.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Volver</a>
    </div>

    <form method="POST" action="{{ route('company.clients.update', $client) }}" class="bg-gradient-to-b from-[var(--primary)] to-[var(--secondary)] p-8 rounded-xl shadow-2xl space-y-8 mt-8 border border-gray-200 max-w-4xl mx-auto relative">
        @csrf
        @method('PUT')

        @include('company.clients.partials.form')

        <!-- Botones -->
        <div class="flex items-center justify-end gap-4 mt-6">
            <button type="submit" class="bg-[var(--primary)] text-white px-6 py-3 rounded-lg shadow-md hover:bg-[var(--primary)]/90 transition-all">Actualizar</button>
            <a href="{{ route('company.clients.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">Cancelar</a>
        </div>
    </form>
</div>
@endsection

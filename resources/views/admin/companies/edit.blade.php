@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow-md rounded-xl p-8 border border-gray-200">

    <h1 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        🏢 Editar Empresa
    </h1>

    <form action="{{ route('admin.companies.update', $company->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Información general -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Empresa</label>
                <input type="text" name="name" value="{{ old('name', $company->name) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-cyan-500 focus:border-cyan-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">NIT</label>
                <input type="text" name="nit" value="{{ old('nit', $company->nit) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-cyan-500 focus:border-cyan-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                <input type="email" name="email" value="{{ old('email', $company->email) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-cyan-500 focus:border-cyan-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                <input type="text" name="phone" value="{{ old('phone', $company->phone) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                <input type="text" name="address" value="{{ old('address', $company->address) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Representante</label>
                <input type="text" name="representative" value="{{ old('representative', $company->representative) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
            </div>
        </div>

        <!-- Logo -->
        <div class="border-t pt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Logo de la Empresa</label>
            <input type="file" name="logo" accept="image/*" id="logoInput" class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-cyan-500">

            @if($company->logo)
                <div class="mt-3">
                    <img id="logoPreview" src="{{ asset('storage/' . $company->logo) }}" alt="Logo actual" class="h-24 object-contain rounded-lg border border-gray-300 p-2 bg-gray-50">
                </div>
            @else
                <img id="logoPreview" src="#" class="hidden h-24 object-contain rounded-lg border border-gray-300 p-2 bg-gray-50">
            @endif
        </div>

        <!-- Colores corporativos -->
        <div class="border-t pt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Color Primario</label>
                <input type="color" name="color_primary" value="{{ old('color_primary', $company->color_primary) }}" class="w-full h-10 rounded-md cursor-pointer">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Color Secundario</label>
                <input type="color" name="color_secondary" value="{{ old('color_secondary', $company->color_secondary) }}" class="w-full h-10 rounded-md cursor-pointer">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Color de Texto</label>
                <input type="color" name="color_text" value="{{ old('color_text', $company->color_text) }}" class="w-full h-10 rounded-md cursor-pointer">
            </div>
        </div>

        <!-- Estado -->
        <div class="border-t pt-6 flex items-center justify-between">
            <label class="text-sm font-medium text-gray-700">Empresa Activa</label>
            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" name="active" value="1" {{ $company->active ? 'checked' : '' }} class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-cyan-500 relative transition-all duration-300"></div>
            </label>
        </div>

        <!-- Botones -->
        <div class="pt-6 flex justify-end space-x-3 border-t">
            <a href="{{ route('admin.companies.index') }}" class="px-5 py-2.5 text-sm rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium transition">
                Cancelar
            </a>
            <button type="submit" class="px-5 py-2.5 text-sm rounded-lg bg-cyan-600 hover:bg-cyan-700 text-white font-medium transition">
                Actualizar Empresa
            </button>
        </div>
    </form>
</div>

<!-- Preview del logo -->
<script>
document.getElementById('logoInput').addEventListener('change', function (event) {
    const [file] = event.target.files;
    if (file) {
        const preview = document.getElementById('logoPreview');
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
    }
});
</script>
@endsection

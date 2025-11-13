@extends('layouts.company')

@section('content')
<div class="animate-fadeIn space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Nuevo memorando</h1>
            <p class="text-sm text-gray-500">Registra la información básica, adjunta los soportes y asigna responsables para el seguimiento.</p>
        </div>
        <a href="{{ route('company.memorandos.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">Volver</a>
    </div>

    <form action="{{ route('company.memorandos.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow p-6 space-y-6">
        @csrf
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label for="code" class="block text-sm font-semibold text-gray-700">Código</label>
                <input id="code" name="code" type="text" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="MEM-2024-024">
            </div>
            <div>
                <label for="employee" class="block text-sm font-semibold text-gray-700">Colaborador</label>
                <input id="employee" name="employee" type="text" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Nombre del colaborador">
            </div>
            <div>
                <label for="subject" class="block text-sm font-semibold text-gray-700">Asunto</label>
                <input id="subject" name="subject" type="text" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Motivo del memorando">
            </div>
            <div>
                <label for="responsible" class="block text-sm font-semibold text-gray-700">Supervisor responsable</label>
                <input id="responsible" name="responsible" type="text" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Nombre del supervisor">
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-semibold text-gray-700">Descripción</label>
            <textarea id="description" name="description" rows="4" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Describe la novedad registrada y las acciones a ejecutar."></textarea>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label for="date" class="block text-sm font-semibold text-gray-700">Fecha del evento</label>
                <input id="date" name="date" type="date" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
            </div>
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700">Estado inicial</label>
                <select id="status" name="status" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                    <option value="draft">Borrador</option>
                    <option value="in_review">En revisión</option>
                    <option value="sent">Enviado</option>
                </select>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Archivos adjuntos</label>
                <p class="text-xs text-gray-500">Carga soportes en PDF, imágenes o archivos comprimidos. Los archivos se almacenarán usando el storage configurado en Laravel.</p>
            </div>
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center bg-gray-50">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
                <p class="mt-2 text-sm text-gray-600">Arrastra y suelta los archivos o</p>
                <label class="mt-4 inline-flex items-center px-4 py-2 bg-[var(--primary)] text-white rounded-lg cursor-pointer hover:bg-blue-600 transition-colors">
                    Seleccionar archivos
                    <input type="file" name="attachments[]" multiple class="hidden">
                </label>
                <p class="mt-2 text-xs text-gray-500">Tamaño máximo 10MB por archivo.</p>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('company.memorandos.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">Cancelar</a>
            <button type="submit" class="px-4 py-2 bg-[var(--primary)] text-white rounded-lg shadow hover:bg-blue-600 transition-colors">Guardar memorando</button>
        </div>
    </form>
</div>
@endsection

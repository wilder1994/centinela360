@extends('layouts.company')

@section('content')
<div class="animate-fadeIn space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Editar memorando</h1>
            <p class="text-sm text-gray-500">Actualiza los datos registrados, agrega comentarios o adjunta nuevos soportes.</p>
        </div>
        <a href="{{ route('company.memorandos.show', 23) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">Ver detalle</a>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <form action="{{ route('company.memorandos.update', 23) }}" method="POST" enctype="multipart/form-data" class="lg:col-span-2 bg-white rounded-xl shadow p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="code" class="block text-sm font-semibold text-gray-700">Código</label>
                    <input id="code" name="code" type="text" value="MEM-2024-023" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                </div>
                <div>
                    <label for="employee" class="block text-sm font-semibold text-gray-700">Colaborador</label>
                    <input id="employee" name="employee" type="text" value="Juan Pérez" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                </div>
                <div>
                    <label for="subject" class="block text-sm font-semibold text-gray-700">Asunto</label>
                    <input id="subject" name="subject" type="text" value="Incumplimiento de protocolos de seguridad" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                </div>
                <div>
                    <label for="responsible" class="block text-sm font-semibold text-gray-700">Supervisor responsable</label>
                    <input id="responsible" name="responsible" type="text" value="María Gómez" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700">Descripción</label>
                <textarea id="description" name="description" rows="4" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">El colaborador omitió el protocolo de registro de ingreso del visitante externo, lo que generó un reporte inmediato por parte del cliente.</textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="date" class="block text-sm font-semibold text-gray-700">Fecha del evento</label>
                    <input id="date" name="date" type="date" value="2024-03-11" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                </div>
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700">Estado</label>
                    <select id="status" name="status" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                        <option value="draft">Borrador</option>
                        <option value="in_review" selected>En revisión</option>
                        <option value="sent">Enviado</option>
                        <option value="closed">Cerrado</option>
                    </select>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Adjuntar nuevos archivos</label>
                    <p class="text-xs text-gray-500">Los archivos se conservarán en el storage de Laravel y podrás reemplazarlos o agregar nuevos soportes.</p>
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
                    <p class="mt-2 text-xs text-gray-500">Formatos permitidos: PDF, JPG, PNG, ZIP.</p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('company.memorandos.show', 23) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-[var(--primary)] text-white rounded-lg shadow hover:bg-blue-600 transition-colors">Guardar cambios</button>
            </div>
        </form>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Seguimiento</h2>
                @include('company.memorandos.partials.timeline')
            </div>

            <div class="bg-white rounded-xl shadow p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-800">Archivos almacenados</h2>
                <ul class="space-y-3 text-sm text-[var(--primary)]">
                    <li class="flex items-center justify-between">
                        <a href="{{ Storage::url('memorandos/MEM-2024-023.pdf') }}" class="flex items-center gap-2 hover:underline">
                            <span class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-50 text-[var(--primary)]">PDF</span>
                            Memorando MEM-2024-023.pdf
                        </a>
                        <button type="button" class="text-xs text-red-500 hover:underline">Eliminar</button>
                    </li>
                    <li class="flex items-center justify-between">
                        <a href="{{ Storage::url('memorandos/acta-compromiso.pdf') }}" class="flex items-center gap-2 hover:underline">
                            <span class="w-8 h-8 flex items-center justify-center rounded-full bg-green-50 text-green-600">DOC</span>
                            Acta de compromiso.docx
                        </a>
                        <button type="button" class="text-xs text-red-500 hover:underline">Eliminar</button>
                    </li>
                </ul>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Comentarios recientes</h2>
                @include('company.memorandos.partials.comments')
            </div>
        </div>
    </div>
</div>
@endsection

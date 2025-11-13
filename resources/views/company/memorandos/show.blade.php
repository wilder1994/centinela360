@extends('layouts.company')

@section('content')
<div class="animate-fadeIn space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">MEM-2024-023</h1>
            <p class="text-sm text-gray-500">Incumplimiento de protocolos de seguridad</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-600 text-xs font-semibold">En revisión</span>
            <a href="{{ route('company.memorandos.edit', 23) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">Editar</a>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Detalles del memorando</h2>
                    <span class="text-xs text-gray-500">Creado: 12 Mar 2024 - 08:45 AM</span>
                </div>
                <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <p class="font-semibold text-gray-800">Colaborador</p>
                        <p>Juan Pérez</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Cargo</p>
                        <p>Guarda de seguridad</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Área responsable</p>
                        <p>Operaciones</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Supervisor asignado</p>
                        <p>María Gómez</p>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-600 leading-relaxed">
                        El colaborador omitió el protocolo de registro de ingreso del visitante externo, lo que generó un reporte inmediato
                        por parte del cliente. Se realizó seguimiento con el supervisor y se acordó realizar una capacitación adicional.
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Seguimiento y comentarios</h2>
                @include('company.memorandos.partials.comments')
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Línea de tiempo</h2>
                @include('company.memorandos.partials.timeline')
            </div>

            <div class="bg-white rounded-xl shadow p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-800">Archivos adjuntos</h2>
                <ul class="space-y-3 text-sm text-[var(--primary)]">
                    <li>
                        <a href="{{ Storage::url('memorandos/MEM-2024-023.pdf') }}" class="flex items-center gap-2 hover:underline">
                            <span class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-50 text-[var(--primary)]">PDF</span>
                            Memorando MEM-2024-023.pdf
                        </a>
                    </li>
                    <li>
                        <a href="{{ Storage::url('memorandos/acta-compromiso.pdf') }}" class="flex items-center gap-2 hover:underline">
                            <span class="w-8 h-8 flex items-center justify-center rounded-full bg-green-50 text-green-600">DOC</span>
                            Acta de compromiso.docx
                        </a>
                    </li>
                    <li>
                        <a href="{{ Storage::url('memorandos/evidencia-fotografica.zip') }}" class="flex items-center gap-2 hover:underline">
                            <span class="w-8 h-8 flex items-center justify-center rounded-full bg-purple-50 text-purple-600">ZIP</span>
                            Evidencia fotográfica.zip
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.company')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-500">{{ $memorandum->code }}</p>
                <h2 class="text-3xl font-semibold text-gray-900">{{ $memorandum->subject }}</h2>
                <p class="text-sm text-gray-600">Emitido {{ optional($memorandum->issued_at)->format('d/m/Y H:i') }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('company.memorandums.edit', $memorandum) }}"
                   class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded shadow hover:bg-blue-700">Editar</a>
                <form action="{{ route('company.memorandums.destroy', $memorandum) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar este memorándum?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-red-600 bg-red-50 rounded hover:bg-red-100">Eliminar</button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white rounded-lg shadow p-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-800">Detalle</h3>
                <p class="text-gray-700 whitespace-pre-line">{{ $memorandum->body }}</p>
            </div>

            <div class="space-y-4">
                <div class="bg-white rounded-lg shadow p-4 space-y-2">
                    <h4 class="text-sm font-semibold text-gray-800">Resumen</h4>
                    <p><span class="font-medium">Estado:</span> {{ $memorandum->status->label() }}</p>
                    <p><span class="font-medium">Empleado:</span> {{ $memorandum->employee?->full_name ?? '—' }}</p>
                    <p><span class="font-medium">Acuse:</span> {{ optional($memorandum->acknowledged_at)->format('d/m/Y H:i') ?? 'Pendiente' }}</p>
                </div>

                <div class="bg-white rounded-lg shadow p-4 space-y-3">
                    <h4 class="text-sm font-semibold text-gray-800">Cambiar estado</h4>
                    <form method="POST" action="{{ route('company.memorandums.status', [$memorandum, 'status' => 'acknowledged']) }}" class="space-y-2">
                        @csrf
                        <input type="hidden" name="notes" value="">
                        <button type="submit" class="w-full px-3 py-2 text-sm font-semibold text-white bg-green-600 rounded hover:bg-green-700">
                            Marcar como recibido
                        </button>
                    </form>
                    <form method="POST" action="{{ route('company.memorandums.status', [$memorandum, 'status' => 'archived']) }}" class="space-y-2">
                        @csrf
                        <input type="hidden" name="notes" value="">
                        <button type="submit" class="w-full px-3 py-2 text-sm font-semibold text-gray-800 bg-gray-200 rounded hover:bg-gray-300">
                            Archivar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Historial de estado</h3>
            <ul class="divide-y divide-gray-200">
                @foreach ($memorandum->statusHistories as $history)
                    <li class="py-3 flex items-center justify-between text-sm">
                        <div>
                            <p class="font-medium text-gray-800">{{ $history->from_status ?? 'Nuevo' }} → {{ $history->to_status }}</p>
                            <p class="text-gray-500">{{ optional($history->created_at)->format('d/m/Y H:i') }}</p>
                        </div>
                        <span class="text-gray-600">{{ $history->changer?->name }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection

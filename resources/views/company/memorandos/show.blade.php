@extends('layouts.company')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $memorandum->title }}</h1>
            <p class="text-sm text-gray-500">Responsable: {{ $memorandum->responsible?->name ?? 'Sin asignar' }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach (\App\Models\Memorandum::STATUSES as $status)
                <form action="{{ route('company.memorandos.status', $memorandum) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="notes" value="{{ __('Cambio rápido de estado') }}">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium shadow-sm transition-colors duration-150 {{ $memorandum->status === $status ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ __(ucfirst(str_replace('_', ' ', $status))) }}
                    </button>
                </form>
            @endforeach
        </div>
    </div>

    @if (session('status'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('status') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-xl p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-700">Detalle</h2>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($memorandum->body)) !!}
                </div>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Estado actual</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ __(ucfirst(str_replace('_', ' ', $memorandum->status))) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Creado</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $memorandum->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Última actualización</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $memorandum->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white shadow rounded-xl p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-700">Notas de cambio de estado</h2>
                <form action="{{ route('company.memorandos.status', $memorandum) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Nuevo estado</label>
                        <select id="status" name="status" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring focus:ring-[var(--primary)] focus:ring-opacity-50" required>
                            @foreach (\App\Models\Memorandum::STATUSES as $status)
                                <option value="{{ $status }}" @selected($memorandum->status === $status)>{{ __(ucfirst(str_replace('_', ' ', $status))) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notas</label>
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring focus:ring-[var(--primary)] focus:ring-opacity-50" placeholder="Describe el motivo del cambio de estado"></textarea>
                    </div>
                    <button type="submit" class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[var(--primary)] hover:bg-[var(--primary-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary)]">
                        Registrar cambio de estado
                    </button>
                </form>
            </div>
        </div>
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-xl p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-700">Historial de estados</h2>
                <ul class="space-y-4">
                    @forelse ($memorandum->statusHistories as $history)
                        <li class="border border-gray-100 rounded-lg p-4">
                            <div class="text-sm text-gray-500">{{ $history->created_at->format('d/m/Y H:i') }}</div>
                            <div class="text-sm font-semibold text-gray-800">
                                {{ __(ucfirst(str_replace('_', ' ', $history->from_status ?? 'inicio'))) }} → {{ __(ucfirst(str_replace('_', ' ', $history->to_status))) }}
                            </div>
                            <div class="text-xs text-gray-500">Por {{ $history->changer?->name }}</div>
                            @if ($history->notes)
                                <p class="mt-2 text-sm text-gray-600">{{ $history->notes }}</p>
                            @endif
                        </li>
                    @empty
                        <li class="text-sm text-gray-500">No hay historial de cambios.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

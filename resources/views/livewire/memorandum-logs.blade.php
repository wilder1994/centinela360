<div class="space-y-3">
    @forelse ($logs as $log)
        <div class="border border-gray-200 rounded-lg p-3 bg-white shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <div class="font-semibold text-sm text-gray-800">
                        {{ $log->user?->name ?? 'Sistema' }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $log->created_at?->format('Y-m-d H:i') }}
                    </div>
                </div>
                <div class="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded">
                    {{ $log->estado_anterior }} → {{ $log->estado_nuevo }}
                </div>
            </div>
            @if ($log->comentario)
                <p class="text-sm text-gray-700 mt-2">{{ $log->comentario }}</p>
            @endif
        </div>
    @empty
        <p class="text-sm text-gray-500">Sin movimientos registrados.</p>
    @endforelse
</div>

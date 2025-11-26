<div class="space-y-4">
    @forelse ($history as $item)
        <div class="relative pl-6">
            <span class="absolute left-0 top-1.5 h-2.5 w-2.5 rounded-full bg-[var(--primary)]"></span>
            <div class="rounded-lg bg-gray-50 p-4">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-gray-800">
                        {{ $item->new_status?->label() }}
                    </p>
                    <span class="text-xs text-gray-500">{{ $item->created_at?->format('d M Y H:i') }}</span>
                </div>
                <p class="mt-1 text-xs text-gray-500">Cambio realizado por {{ $item->changer?->name }}
                    @if ($item->previous_status)
                        desde {{ $item->previous_status?->label() }}
                    @endif
                </p>
                @if ($item->comment)
                    <p class="mt-2 text-sm text-gray-600">{{ $item->comment }}</p>
                @endif
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-500">Aún no se registra historial de estados.</p>
    @endforelse
</div>

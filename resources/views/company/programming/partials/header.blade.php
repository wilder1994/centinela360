@php
    $tabs = [
        [
            'key' => 'index',
            'label' => 'Resumen',
            'href' => route('company.programming.index'),
            'active' => request()->routeIs('company.programming.index'),
        ],
        [
            'key' => 'create',
            'label' => 'Crear programacion',
            'href' => route('company.programming.create'),
            'active' => request()->routeIs('company.programming.create'),
        ],
    ];
@endphp

<div class="w-[calc(100%+4rem)] -ml-8 -mr-8 -mt-8 mb-6">
    <div class="w-full bg-white border border-gray-200 shadow-sm">
        <div class="flex items-center gap-2 px-4 sm:px-6 py-3">
            @foreach ($tabs as $tab)
                <a href="{{ $tab['href'] }}"
                   class="px-3 py-2 text-sm font-semibold transition border-b-2
                          {{ $tab['active'] ? 'text-gray-900 border-[var(--primary)]' : 'text-gray-500 border-transparent hover:text-gray-800 hover:border-[var(--primary)]/60' }}">
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</div>

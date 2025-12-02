@props([
    'name',
    // Tamaño por defecto consistente en todo el sistema.
    'class' => 'w-5 h-5',
])

@php
    // Mapa centralizado: agrega aquí nuevos íconos en /resources/views/components/icons.
    // Nota: los nombres son relativos a /resources/views/components, por eso omitimos el prefijo "components."
    $icons = [
        'dashboard'   => 'icons.dashboard',
        'briefcase'   => 'icons.briefcase',
        'users'       => 'icons.users',
        'file'        => 'icons.file',
        'bell'        => 'icons.bell',
        'memo'        => 'icons.memo',
        'calendar'    => 'icons.calendar',
        'clients'     => 'icons.clients',
        'employees'   => 'icons.employees',
        'stats'       => 'icons.stats',
        'settings'    => 'icons.settings',
        'doc-plus'    => 'icons.doc-plus',
        'clock'       => 'icons.clock',
        'progress'    => 'icons.progress',
        'check'       => 'icons.check',
        'shield'      => 'icons.shield',
        'megaphone'   => 'icons.megaphone',
        'laptop'      => 'icons.laptop',
        'phone'       => 'icons.phone',
    ];

    $component = $icons[$name] ?? null;
@endphp

@if($component)
    <x-dynamic-component
        :component="$component"
        {{ $attributes->class([$class]) }}
    />
@endif

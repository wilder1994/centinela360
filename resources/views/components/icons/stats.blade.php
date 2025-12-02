@props(['class' => 'w-5 h-5'])
<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
    <rect x="4" y="12" width="3.5" height="7.5" rx="1" fill="#C7D2FE" stroke="#0F172A" stroke-width="1.1"/>
    <rect x="10.25" y="9" width="3.5" height="10.5" rx="1" fill="#38BDF8" stroke="#0F172A" stroke-width="1.1"/>
    <rect x="16.5" y="6" width="3.5" height="13.5" rx="1" fill="#0EA5E9" opacity="0.9" stroke="#0F172A" stroke-width="1.1"/>
    <path d="M4 6.5 8.5 9l4-3 5 3 2.5-1.5" stroke="#0F172A" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

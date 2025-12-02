@props(['class' => 'w-5 h-5'])
<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
    <rect x="4" y="5" width="16" height="15" rx="2" fill="#E0F2FE" stroke="#0F172A" stroke-width="1.2"/>
    <path d="M8 3.5v3" stroke="#0F172A" stroke-width="1.2" stroke-linecap="round"/>
    <path d="M16 3.5v3" stroke="#0F172A" stroke-width="1.2" stroke-linecap="round"/>
    <path d="M4 9h16" stroke="#0F172A" stroke-width="1.2"/>
    <rect x="9" y="12" width="6" height="4" rx="0.8" fill="#38BDF8" stroke="#0F172A" stroke-width="1.1"/>
    <path d="m10.5 14 1.5 1.3 1.8-2.3" stroke="#F8FAFC" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

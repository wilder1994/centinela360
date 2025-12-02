@props(['class' => 'w-5 h-5'])
<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
    <rect x="6" y="5.5" width="12" height="9" rx="1.2" fill="#E0F2FE" stroke="#0F172A" stroke-width="1.2"/>
    <path d="M4.5 16h15" stroke="#0F172A" stroke-width="1.2" stroke-linecap="round"/>
    <rect x="9" y="8" width="6" height="3.5" rx="0.8" fill="#38BDF8" opacity="0.8"/>
    <path d="M5 18.5h14" stroke="#0F172A" stroke-width="1.2" stroke-linecap="round"/>
</svg>

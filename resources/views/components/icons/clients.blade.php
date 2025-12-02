@props(['class' => 'w-5 h-5'])
<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
    <rect x="3.5" y="5" width="17" height="14" rx="1.8" fill="#E0F2FE" stroke="#0F172A" stroke-width="1.2"/>
    <rect x="6" y="7.5" width="7" height="3.5" rx="1.2" fill="#38BDF8" opacity="0.8"/>
    <path d="M6.5 14.5c.6-1.3 2-2.2 3.5-2.2s2.9.9 3.5 2.2" stroke="#0F172A" stroke-width="1.1" stroke-linecap="round"/>
    <path d="M14.5 9h3" stroke="#0F172A" stroke-width="1.1" stroke-linecap="round"/>
    <path d="M14.5 12h3" stroke="#0F172A" stroke-width="1.1" stroke-linecap="round"/>
    <path d="M14.5 15h3" stroke="#0F172A" stroke-width="1.1" stroke-linecap="round"/>
</svg>

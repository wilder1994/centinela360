@props(['class' => 'w-5 h-5'])
<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
    <rect x="3" y="7" width="18" height="12" rx="2" fill="#0EA5E9" opacity="0.9"/>
    <path d="M8 7.5V6a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v1.5" stroke="#0F172A" stroke-width="1.4" stroke-linecap="round"/>
    <path d="M3 12h18" stroke="#0F172A" stroke-width="1.4" stroke-linecap="round"/>
    <rect x="10.5" y="11" width="3" height="2.2" rx="0.6" fill="#F8FAFC" stroke="#0F172A" stroke-width="1.1"/>
</svg>

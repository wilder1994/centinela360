@props(['class' => 'w-5 h-5'])
<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
    <rect x="4" y="4.5" width="16" height="15" rx="1.8" fill="#EEF2FF" stroke="#0F172A" stroke-width="1.2"/>
    <rect x="7" y="7" width="10" height="3" rx="0.9" fill="#C7D2FE"/>
    <circle cx="12" cy="12" r="2.5" fill="#6366F1" opacity="0.9" stroke="#0F172A" stroke-width="1.1"/>
    <path d="M8.5 16.5c.6-1.3 1.9-2.2 3.5-2.2s2.9.9 3.5 2.2" stroke="#0F172A" stroke-width="1.1" stroke-linecap="round"/>
</svg>

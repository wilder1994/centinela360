@props(['class' => 'w-5 h-5'])
<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
    <rect x="8" y="3.5" width="8" height="17" rx="1.8" fill="#E0F2FE" stroke="#0F172A" stroke-width="1.2"/>
    <rect x="10" y="6" width="4" height="8" rx="0.8" fill="#38BDF8" opacity="0.85"/>
    <circle cx="12" cy="16.8" r="0.7" fill="#0F172A"/>
    <path d="M10 20h4" stroke="#0F172A" stroke-width="1.2" stroke-linecap="round"/>
</svg>

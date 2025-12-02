@props(['class' => 'w-5 h-5'])
<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
    <rect x="5" y="3.5" width="14" height="17" rx="1.8" fill="#FEF3C7" stroke="#0F172A" stroke-width="1.2"/>
    <rect x="8" y="3.5" width="8" height="2.5" rx="0.8" fill="#FACC15" opacity="0.7"/>
    <path d="M8.5 9h7" stroke="#0F172A" stroke-width="1.2" stroke-linecap="round"/>
    <path d="M8.5 12h6" stroke="#0F172A" stroke-width="1.2" stroke-linecap="round"/>
    <path d="M8.5 15h5" stroke="#0F172A" stroke-width="1.2" stroke-linecap="round"/>
</svg>

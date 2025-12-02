@props(['class' => 'w-5 h-5'])
<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
    <circle cx="8" cy="9" r="3.25" fill="#38BDF8" stroke="#0F172A" stroke-width="1.3"/>
    <circle cx="17" cy="8" r="2.75" fill="#C7D2FE" stroke="#0F172A" stroke-width="1.2"/>
    <path d="M3.5 18.5c0-2.6 2.5-4.7 5.5-4.7s5.5 2.1 5.5 4.7" fill="#E0F2FE" stroke="#0F172A" stroke-width="1.2" stroke-linecap="round"/>
    <path d="M14 16.4c.6-.8 1.7-1.4 3-1.4 1.9 0 3.5 1.4 3.5 3.1" stroke="#0F172A" stroke-width="1.1" stroke-linecap="round"/>
</svg>

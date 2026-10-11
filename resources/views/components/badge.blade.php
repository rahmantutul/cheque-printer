@props(['color' => 'gray'])

@php
    $colors = [
        'gray' => 'bg-slate-100 text-slate-700',
        'success' => 'bg-green-50 text-green-800',
        'warning' => 'bg-amber-50 text-amber-900',
        'danger' => 'bg-red-50 text-red-800',
        'info' => 'bg-blue-50 text-admin',
        'primary' => 'bg-red-50 text-primary-dark',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2 py-1 text-xs font-semibold '.($colors[$color] ?? $colors['gray'])]) }}>{{ $slot }}</span>

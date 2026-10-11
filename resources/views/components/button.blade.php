@props(['variant' => 'primary', 'type' => 'button', 'href' => null, 'size' => 'md', 'disabled' => false])

@php
    $variants = [
        'primary' => 'border-transparent bg-primary text-white hover:bg-primary-dark active:bg-primary-dark focus-visible:ring-primary-dark',
        'secondary' => 'border-border bg-surface text-text hover:bg-surface-alt active:bg-slate-100 focus-visible:ring-text-muted',
        'danger' => 'border-transparent bg-danger text-white hover:bg-red-700 active:bg-red-800 focus-visible:ring-danger',
        'ghost' => 'border-transparent bg-transparent text-text-muted hover:bg-surface-alt hover:text-text active:bg-slate-100 focus-visible:ring-text-muted',
    ];
    $sizes = ['sm' => 'min-h-8 px-3 py-2 text-xs', 'md' => 'min-h-10 px-4 py-2 text-sm', 'lg' => 'min-h-12 px-6 py-3 text-base'];
    $classes = 'inline-flex items-center justify-center gap-2 rounded border font-semibold transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 '
        .($variants[$variant] ?? $variants['primary']).' '.($sizes[$size] ?? $sizes['md'])
        .($disabled ? ' pointer-events-none opacity-50' : '');
@endphp

@if ($href !== null)
    <a @if (! $disabled) href="{{ $href }}" @else role="link" aria-disabled="true" tabindex="-1" @click.prevent.stop @endif {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" @disabled($disabled) {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif

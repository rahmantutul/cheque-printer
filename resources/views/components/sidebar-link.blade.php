@props(['route', 'icon', 'label', 'active' => false])

@php
    $available = Route::has($route);
@endphp

<a href="{{ $available ? route($route) : url('#') }}"
    @if (! $available) title="Coming in a later phase" aria-disabled="true" @click.prevent @endif
    @if ($active && $available) aria-current="page" @endif
    @class([
        'flex min-h-12 items-center gap-3 rounded px-3 py-3 text-sm transition-colors focus-visible:ring-white',
        'bg-white/10 font-semibold' => $active && $available,
        'hover:bg-white/5' => ! $active || ! $available,
        'text-white/70' => ! $available,
    ])>
    <x-icon :name="$icon" />
    <span>{{ $label }}</span>
    @if (! $available)
        <span class="sr-only">Coming in a later phase</span>
    @endif
</a>

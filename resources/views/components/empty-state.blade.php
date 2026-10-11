@props(['title', 'description', 'icon' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center px-6 py-12 text-center']) }}>
    @if ($icon)
        <div class="mb-4 rounded-lg bg-surface-alt p-4 text-text-muted"><x-icon :name="$icon" class="h-8 w-8" /></div>
    @endif
    <h2 class="text-lg font-semibold text-text">{{ $title }}</h2>
    <p class="mt-2 max-w-md text-sm leading-6 text-text-muted">{{ $description }}</p>
    @isset($action)
        <div class="mt-6">{{ $action }}</div>
    @endisset
</div>

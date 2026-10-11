@props(['title', 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row']) }}>
    <div class="min-w-0">
        <h1 class="break-words text-2xl font-semibold tracking-tight text-text">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-1 text-sm leading-6 text-text-muted">{{ $subtitle }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="flex shrink-0 flex-wrap gap-2">{{ $actions }}</div>
    @endisset
</div>

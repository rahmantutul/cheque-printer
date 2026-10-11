@props(['title' => null, 'padding' => 'p-6'])

<section {{ $attributes->merge(['class' => 'min-w-0 rounded-lg border border-border bg-surface shadow-sm']) }}>
    @if ($title)
        <div class="border-b border-border px-6 py-4">
            <h2 class="text-base font-semibold text-text">{{ $title }}</h2>
        </div>
    @endif
    <div class="{{ $padding }}">{{ $slot }}</div>
    @isset($footer)
        <div class="border-t border-border px-6 py-4">{{ $footer }}</div>
    @endisset
</section>

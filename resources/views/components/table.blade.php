@props([])

<div class="overflow-x-auto rounded-lg border border-border bg-surface shadow-sm" tabindex="0" role="region" aria-label="{{ $attributes->get('aria-label', 'Data table') }}">
    <table {{ $attributes->except('aria-label')->merge(['class' => 'min-w-full divide-y divide-border text-left text-sm']) }}>
        <thead class="bg-surface-alt text-xs font-semibold uppercase tracking-wide text-text-muted [&_th]:px-6 [&_th]:py-4">{{ $thead }}</thead>
        <tbody class="divide-y divide-border text-text [&_td]:px-6 [&_td]:py-4">{{ $tbody }}</tbody>
    </table>
</div>

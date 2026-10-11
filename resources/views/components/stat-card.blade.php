@props(['label', 'icon', 'context' => 'user'])

<x-card>
    <div class="flex items-center justify-between gap-4">
        <p class="text-sm font-medium text-text-muted">{{ $label }}</p>
        <span @class(['rounded p-2', 'bg-blue-50 text-admin' => $context === 'admin', 'bg-teal-50 text-user' => $context !== 'admin'])>
            <x-icon :name="$icon" />
        </span>
    </div>
    <p class="mt-6 text-3xl font-semibold tracking-tight text-text" aria-label="Not yet available">—</p>
    {{-- TODO (Phase 3): Read the authorized count from the dashboard service. --}}
</x-card>

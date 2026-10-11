@props([])

@php
    $types = [
        'success' => ['class' => 'border-success/20 bg-green-50 text-green-800', 'label' => 'Success'],
        'error' => ['class' => 'border-danger/20 bg-red-50 text-red-800', 'label' => 'Error'],
        'warning' => ['class' => 'border-warning/30 bg-amber-50 text-amber-900', 'label' => 'Warning'],
        'info' => ['class' => 'border-admin/20 bg-blue-50 text-admin', 'label' => 'Information'],
        'status' => ['class' => 'border-admin/20 bg-blue-50 text-admin', 'label' => 'Status'],
    ];
@endphp

<div class="space-y-3">
    @foreach ($types as $type => $settings)
        @if (session($type))
            <div x-data="{ visible: true }" :class="{ 'hidden': !visible }"
                role="{{ $type === 'error' ? 'alert' : 'status' }}"
                class="mb-6 flex items-start gap-3 rounded-lg border p-4 text-sm {{ $settings['class'] }}">
                <x-icon :name="$type === 'success' ? 'check-circle' : 'shield-exclamation'" />
                <p class="min-w-0 flex-1 break-words leading-6"><span class="sr-only">{{ $settings['label'] }}: </span>{{ session($type) }}</p>
                <button type="button" @click="visible = false" class="shrink-0 rounded p-1 hover:bg-black/5" aria-label="Dismiss {{ strtolower($settings['label']) }} message">
                    <x-icon name="x-mark" class="h-4 w-4" />
                </button>
            </div>
        @endif
    @endforeach
</div>

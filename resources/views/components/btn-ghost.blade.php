@props(['type' => 'button', 'href' => null, 'size' => 'md', 'disabled' => false])

<x-button variant="ghost" :type="$type" :href="$href" :size="$size" :disabled="$disabled" {{ $attributes }}>{{ $slot }}</x-button>

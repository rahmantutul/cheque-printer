@props(['type' => 'button'])

<x-btn-secondary :type="$type" {{ $attributes }}>{{ $slot }}</x-btn-secondary>

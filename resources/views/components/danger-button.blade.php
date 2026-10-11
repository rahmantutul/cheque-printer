@props(['type' => 'submit'])

<x-btn-danger :type="$type" {{ $attributes }}>{{ $slot }}</x-btn-danger>

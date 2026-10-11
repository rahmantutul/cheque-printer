@props(['type' => 'submit'])

<x-btn-primary :type="$type" {{ $attributes }}>{{ $slot }}</x-btn-primary>

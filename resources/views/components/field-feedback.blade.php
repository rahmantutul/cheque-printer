@props(['name', 'id', 'help' => null])

@if ($help)
    <p id="{{ $id }}-help" class="mt-2 text-sm text-text-muted">{{ $help }}</p>
@endif
@error($name)
    <p id="{{ $id }}-error" class="mt-2 text-sm text-danger" role="alert">{{ $message }}</p>
@enderror

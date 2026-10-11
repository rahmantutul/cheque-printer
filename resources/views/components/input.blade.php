@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false, 'placeholder' => null, 'help' => null])

@php
    $id = $attributes->get('id', str_replace(['[', ']', '.'], ['-', '', '-'], $name));
    $key = trim(str_replace(['[', ']'], ['.', ''], $name), '.');
    $describedBy = trim(($help ? $id.'-help ' : '').($errors->has($key) ? $id.'-error' : ''));
@endphp

<div>
    <label for="{{ $id }}" class="mb-2 block text-sm font-medium text-text">{{ $label }} @if ($required)<span aria-hidden="true" class="text-danger">*</span>@endif</label>
    <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" @required($required)
        @if (! in_array($type, ['password', 'file'], true)) value="{{ old($key, $value) }}" @endif
        @if ($placeholder !== null) placeholder="{{ $placeholder }}" @endif
        @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
        aria-invalid="{{ $errors->has($key) ? 'true' : 'false' }}"
        {{ $attributes->except('id')->class(['form-control', 'form-control-error' => $errors->has($key)]) }}>
    <x-field-feedback :name="$key" :id="$id" :help="$help" />
</div>

@props(['name', 'label', 'value' => null, 'rows' => 4, 'required' => false, 'help' => null])

@php
    $id = $attributes->get('id', str_replace(['[', ']', '.'], ['-', '', '-'], $name));
    $key = trim(str_replace(['[', ']'], ['.', ''], $name), '.');
    $describedBy = trim(($help ? $id.'-help ' : '').($errors->has($key) ? $id.'-error' : ''));
@endphp

<div>
    <label for="{{ $id }}" class="mb-2 block text-sm font-medium text-text">{{ $label }} @if ($required)<span aria-hidden="true" class="text-danger">*</span>@endif</label>
    <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" @required($required)
        @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
        aria-invalid="{{ $errors->has($key) ? 'true' : 'false' }}"
        {{ $attributes->except('id')->class(['form-control', 'form-control-error' => $errors->has($key)]) }}>{{ old($key, $value) }}</textarea>
    <x-field-feedback :name="$key" :id="$id" :help="$help" />
</div>

@props(['name', 'label', 'options' => [], 'selected' => null, 'required' => false, 'placeholder' => null, 'help' => null])

@php
    $id = $attributes->get('id', str_replace(['[', ']', '.'], ['-', '', '-'], $name));
    $key = trim(str_replace(['[', ']'], ['.', ''], $name), '.');
    $current = old($key, $selected);
    $describedBy = trim(($help ? $id.'-help ' : '').($errors->has($key) ? $id.'-error' : ''));
@endphp

<div>
    <label for="{{ $id }}" class="mb-2 block text-sm font-medium text-text">{{ $label }} @if ($required)<span aria-hidden="true" class="text-danger">*</span>@endif</label>
    <select id="{{ $id }}" name="{{ $name }}" @required($required)
        @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
        aria-invalid="{{ $errors->has($key) ? 'true' : 'false' }}"
        {{ $attributes->except('id')->class(['form-control', 'form-control-error' => $errors->has($key)]) }}>
        @if ($placeholder !== null)
            <option value="" @selected($current === null || $current === '')>{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) $current === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
    <x-field-feedback :name="$key" :id="$id" :help="$help" />
</div>

@props(['name', 'label', 'value' => false, 'help' => null])

@php
    $id = $attributes->get('id', str_replace(['[', ']', '.'], ['-', '', '-'], $name));
    $key = trim(str_replace(['[', ']'], ['.', ''], $name), '.');
    $checked = filter_var(old($key, $value), FILTER_VALIDATE_BOOLEAN);
    $describedBy = trim(($help ? $id.'-help ' : '').($errors->has($key) ? $id.'-error' : ''));
@endphp

<div>
    <div class="flex items-start gap-3">
        <input type="hidden" name="{{ $name }}" value="0" @disabled($attributes->get('disabled', false))>
        <input id="{{ $id }}" name="{{ $name }}" type="checkbox" value="1" @checked($checked)
            @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
            aria-invalid="{{ $errors->has($key) ? 'true' : 'false' }}"
            {{ $attributes->except('id')->class(['mt-1 h-4 w-4 rounded border-border text-primary-dark focus:ring-primary-dark disabled:opacity-50', 'border-danger' => $errors->has($key)]) }}>
        <label for="{{ $id }}" class="text-sm leading-6 text-text">{{ $label }}</label>
    </div>
    <x-field-feedback :name="$key" :id="$id" :help="$help" />
</div>

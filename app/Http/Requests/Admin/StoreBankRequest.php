<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBankRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        return ['name' => ['required', 'string', 'max:255', 'unique:banks,name'], 'country' => ['required', 'string', 'max:100'], 'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg', 'max:2048'], 'is_default' => ['sometimes', 'boolean']];
    }
}

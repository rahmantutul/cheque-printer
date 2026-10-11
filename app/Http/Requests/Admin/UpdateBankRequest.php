<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBankRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        return ['name' => ['required', 'string', 'max:255', Rule::unique('banks', 'name')->ignore($this->route('bank'))], 'country' => ['required', 'string', 'max:100'], 'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg', 'max:2048'], 'is_default' => ['sometimes', 'boolean']];
    }
}

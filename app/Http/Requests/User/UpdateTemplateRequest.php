<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isAdmin() || auth()->id() === $this->route('template')->created_by);
    }

    public function rules(): array
    {
        return ['bank_id' => ['required', 'exists:banks,id'], 'name' => ['required', 'string', 'max:255'], 'background_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:4096'], 'width_mm' => ['required', 'numeric', 'between:50,500'], 'height_mm' => ['required', 'numeric', 'between:30,300'], 'font_size_pt' => ['nullable', 'integer', 'between:8,16'], 'enabled_fields' => ['nullable', 'array'], 'enabled_fields.*' => ['in:payee,amount_digits,amount_words_1,amount_words_2,date,memo'], 'field_positions' => ['required', 'string']];
    }
}

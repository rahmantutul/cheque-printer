<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FoundationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->is_active;
    }

    public function rules(): array
    {
        // Foundation actions accept no business fields and never persist input.
        return [];
    }
}

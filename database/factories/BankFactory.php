<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company().' Bank',
            'country' => 'Bangladesh',
            'logo_path' => null,
            'is_default' => false,
            'created_by' => User::factory(),
        ];
    }
}

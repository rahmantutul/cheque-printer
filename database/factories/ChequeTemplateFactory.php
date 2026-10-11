<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChequeTemplateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'bank_id' => Bank::factory(),
            'name' => fake()->words(3, true),
            'width_mm' => 203,
            'height_mm' => 90,
            'field_positions' => [],
            'created_by' => User::factory(),
        ];
    }
}

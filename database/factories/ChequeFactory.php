<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChequeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'bank_id' => Bank::factory(),
            'template_id' => null,
            'payee_name' => fake()->name(),
            'amount' => fake()->randomFloat(2, 1, 100000),
            'cheque_date' => fake()->date(),
            'status' => 'draft',
        ];
    }
}

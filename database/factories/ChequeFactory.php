<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Bank;
use App\Models\User;
use App\Services\AmountInWordsService;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChequeFactory extends Factory
{
    public function definition(): array
    {
        $amount = fake()->randomFloat(2, 1000, 500000);
        $words = app(AmountInWordsService::class)->toEnglish($amount);
        return [
            'user_id' => User::factory(),
            'bank_id' => Bank::factory(),
            'template_id' => null,
            'payee_name' => fake()->company(),
            'amount' => $amount,
            'amount_in_words' => $words,
            'cheque_date' => fake()->dateTimeBetween('-30 days', 'now'),
            'status' => 'draft',
            'printed_at' => null,
        ];
    }
}

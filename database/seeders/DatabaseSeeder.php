<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\User;
use App\Models\Cheque;
use App\Models\ChequeTemplate;
use App\Services\AmountInWordsService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Demo Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'company_name' => 'Demo Admin Co',
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'user@example.com',
            'password' => 'password',
            'role' => 'user',
            'company_name' => 'Demo User Co',
            'is_active' => true,
        ]);

        foreach (['Islami Bank Bangladesh', 'BRAC Bank', 'Dutch-Bangla Bank', 'City Bank', 'Eastern Bank'] as $name) {
            Bank::factory()->create([
                'name' => $name,
                'country' => 'Bangladesh',
                'is_default' => $name === 'Islami Bank Bangladesh',
                'created_by' => $admin->id,
            ]);
        }

        $brac = Bank::where('name', 'BRAC Bank')->first();
        $template = $brac?->chequeTemplates()->where('name', 'like', '%BRAC%')->first();
        foreach ([2500, 15000, 125000, 5000000, 12345678.90] as $index => $amount) {
            $words = app(AmountInWordsService::class)->toEnglish((float) $amount);
            Cheque::create(['user_id' => $user->id, 'bank_id' => $brac?->id ?? Bank::first()->id, 'template_id' => $template?->id, 'payee_name' => 'Demo Payee '.($index + 1), 'amount' => $amount, 'amount_in_words' => $words, 'cheque_date' => now()->subDays($index), 'status' => $index < 2 ? 'draft' : 'printed', 'printed_at' => $index < 2 ? null : now()->subDays($index)]);
        }
    }
}

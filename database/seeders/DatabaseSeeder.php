<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\User;
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

        User::factory()->create([
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
    }
}

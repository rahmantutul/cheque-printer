<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Bank;
use App\Models\User;

class BankPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Bank $bank): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Bank $bank): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Bank $bank): bool
    {
        return $user->isAdmin();
    }
}

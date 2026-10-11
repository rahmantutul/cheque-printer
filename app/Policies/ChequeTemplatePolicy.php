<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ChequeTemplate;
use App\Models\User;

class ChequeTemplatePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ChequeTemplate $template): bool
    {
        return $user->isAdmin() || $user->id === $template->created_by;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ChequeTemplate $template): bool
    {
        return $this->view($user, $template);
    }

    public function delete(User $user, ChequeTemplate $template): bool
    {
        return $this->view($user, $template);
    }
}

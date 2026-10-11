<?php
declare(strict_types=1);
namespace App\Policies;
use App\Models\Cheque;
use App\Models\User;
class ChequePolicy { public function viewAny(User $user): bool{return true;} public function view(User $user, Cheque $cheque): bool{return $user->isAdmin()||$user->id===$cheque->user_id;} public function create(User $user): bool{return true;} public function update(User $user, Cheque $cheque): bool{return $user->id===$cheque->user_id&&$cheque->status==='draft';} public function delete(User $user, Cheque $cheque): bool{return $this->update($user,$cheque);} }

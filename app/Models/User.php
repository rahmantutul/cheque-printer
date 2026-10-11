<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'company_name',
        'company_logo',
        'is_active',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function cheques(): HasMany
    {
        return $this->hasMany(Cheque::class);
    }

    public function banks(): HasMany
    {
        return $this->hasMany(Bank::class, 'created_by');
    }
}

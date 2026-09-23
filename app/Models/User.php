<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'company_name',
        'wallet_balance',
        'latitude',
        'longitude',
        'last_location_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin', 'operations']);
    }

    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }

    public function isFranchise(): bool
    {
        return $this->role === 'franchise';
    }

    public function isRider(): bool
    {
        return $this->role === 'rider';
    }
}


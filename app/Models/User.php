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
        'brand_name',
        'gstin',
        'pan_number',
        'business_type',
        'company_address',
        'company_city',
        'company_state',
        'company_pincode',
        'website',
        'support_phone',
        'bank_name',
        'account_number',
        'ifsc_code',
        'account_holder_name',
        'wallet_balance',
        'permissions',
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
            'permissions' => 'array',
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

    public function kyc()
    {
        return $this->hasOne(Kyc::class);
    }

    public function isKycApproved(): bool
    {
        return $this->kyc && $this->kyc->status === 'approved';
    }
}


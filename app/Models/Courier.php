<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    protected $fillable = [
        'name',
        'mode', // 'sandbox' or 'production'
        'api_credentials',
        'is_active'
    ];

    protected $casts = [
        'api_credentials' => 'encrypted:array',
        'is_active' => 'boolean',
    ];
}

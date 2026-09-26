<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('global_settings');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('global_settings');
        });
    }
}


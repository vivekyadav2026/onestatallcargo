<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    protected $fillable = [
        'name',
        'mode', // 'sandbox' or 'production'
        'api_credentials',
        'is_active',
        'markup_type',
        'markup_value'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getApiCredentialsAttribute($value)
    {
        if (empty($value)) {
            return [];
        }

        try {
            // Try decrypting without unserializing (raw JSON)
            $decrypted = decrypt($value, false);
            $json = json_decode($decrypted, true);
            if (is_array($json)) {
                return $json;
            }

            $unserialized = @unserialize($decrypted);
            if (is_array($unserialized)) {
                return $unserialized;
            }

            return [];
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            // MAC invalid or different APP_KEY or raw JSON
            $json = json_decode($value, true);
            return is_array($json) ? $json : [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function setApiCredentialsAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['api_credentials'] = null;
            return;
        }

        try {
            $this->attributes['api_credentials'] = encrypt($value);
        } catch (\Throwable $e) {
            $this->attributes['api_credentials'] = is_array($value) ? json_encode($value) : $value;
        }
    }
}

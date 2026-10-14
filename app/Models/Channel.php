<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'platform',
        'name',
        'store_url',
        'consumer_key',
        'consumer_secret',
        'webhook_secret',
        'is_active',
        'last_sync_at',
        'synced_orders_count',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_sync_at' => 'datetime',
        'settings' => 'array',
        'synced_orders_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get real-time webhook URL for this channel.
     */
    public function getWebhookUrlAttribute(): string
    {
        return url('/api/v1/integrations/' . $this->platform . '/webhook/' . $this->user_id);
    }

    /**
     * Scope active channels.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

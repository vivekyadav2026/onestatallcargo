<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaEvidence extends Model
{
    protected $table = 'media_evidence';
    protected $fillable = ['shipment_id', 'user_id', 'type', 'media_type', 'media_url', 'remarks'];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

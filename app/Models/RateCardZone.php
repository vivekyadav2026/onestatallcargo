<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RateCardZone extends Model
{
    protected $guarded = [];

    public function rate_card()
    {
        return $this->belongsTo(RateCard::class);
    }
}

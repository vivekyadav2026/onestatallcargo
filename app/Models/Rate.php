<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = [
        'zone_type',
        'base_rate',
        'additional_weight_rate',
        'rto_surcharge',
        'cod_surcharge',
    ];
}

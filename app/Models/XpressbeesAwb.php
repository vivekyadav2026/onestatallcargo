<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XpressbeesAwb extends Model
{
    protected $fillable = ['awb_number', 'status', 'order_id', 'type'];
}

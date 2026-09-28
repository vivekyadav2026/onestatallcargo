<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceablePincode extends Model
{
    protected $fillable = ['pincode', 'city', 'state', 'franchise_id', 'is_active'];

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'contact_person', 'phone', 'email', 
        'address', 'city', 'state', 'pincode', 'is_default'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

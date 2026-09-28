<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Franchise extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'owner_name',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'status',
        'serviceable_pincodes'
    ];

    protected $casts = [
        'serviceable_pincodes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

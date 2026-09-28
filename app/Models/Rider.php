<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    public function hub()
    {
        return $this->belongsTo(Hub::class);
    }
}

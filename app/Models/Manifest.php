<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Manifest extends Model
{
    protected $guarded = [];

    public function bag()
    {
        return $this->belongsTo(Bag::class);
    }

    public function sourceHub()
    {
        return $this->belongsTo(Hub::class, 'source_hub_id');
    }
    
    public function destinationHub()
    {
        return $this->belongsTo(Hub::class, 'destination_hub_id');
    }
    
    public function franchise()
    {
        return $this->belongsTo(Franchise::class, 'franchise_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

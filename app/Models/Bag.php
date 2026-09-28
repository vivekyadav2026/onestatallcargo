<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Bag extends Model
{
    protected $guarded = [];

    public function hub()
    {
        return $this->belongsTo(Hub::class, 'hub_id');
    }
    
    public function destinationHub()
    {
        return $this->belongsTo(Hub::class, 'destination_hub_id');
    }

    public function franchise()
    {
        return $this->belongsTo(Franchise::class, 'franchise_id');
    }

    public function sealer()
    {
        return $this->belongsTo(User::class, 'sealed_by');
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function manifests()
    {
        return $this->hasMany(Manifest::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

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
        'district',
        'state',
        'status',
        'serviceable_pincodes',
        'franchise_level',
        'parent_franchise_id',
        'delivery_commission_type',
        'delivery_commission_rate',
        'pickup_commission_type',
        'pickup_commission_rate',
        'state_royalty_type',
        'state_royalty_rate',
    ];

    protected $casts = [
        'serviceable_pincodes' => 'array',
        'delivery_commission_rate' => 'decimal:2',
        'pickup_commission_rate' => 'decimal:2',
        'state_royalty_rate' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::saving(function (Franchise $franchise) {
            // Rule 1: A State franchise cannot have a parent franchise
            if ($franchise->franchise_level === 'state') {
                $franchise->parent_franchise_id = null;
            }

            // Rule 2: Self-referencing is strictly forbidden
            if ($franchise->parent_franchise_id && $franchise->id && $franchise->parent_franchise_id == $franchise->id) {
                throw new InvalidArgumentException('A franchise cannot be its own parent.');
            }

            // Rule 3: A District franchise parent must be a State franchise
            if ($franchise->parent_franchise_id) {
                $parent = static::find($franchise->parent_franchise_id);
                if ($parent && $parent->franchise_level !== 'state') {
                    throw new InvalidArgumentException('District franchise parent must be a State-level franchise.');
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parentFranchise()
    {
        return $this->belongsTo(Franchise::class, 'parent_franchise_id');
    }

    public function districtFranchises()
    {
        return $this->hasMany(Franchise::class, 'parent_franchise_id');
    }

    public function commissions()
    {
        return $this->hasMany(FranchiseCommission::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'franchise_id');
    }

    public function stateShipments()
    {
        return $this->hasMany(Shipment::class, 'state_franchise_id');
    }

    public function pickupShipments()
    {
        return $this->hasMany(Shipment::class, 'pickup_franchise_id');
    }

    public function scopeState($query)
    {
        return $query->where('franchise_level', 'state');
    }

    public function scopeDistrict($query)
    {
        return $query->where('franchise_level', 'district');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function isState(): bool
    {
        return $this->franchise_level === 'state';
    }

    public function isDistrict(): bool
    {
        return $this->franchise_level === 'district';
    }
}

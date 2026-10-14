<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FranchiseCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'franchise_id',
        'shipment_id',
        'commission_type',
        'rate_type',
        'rate_applied',
        'amount',
        'status',
        'wallet_transaction_id',
        'credited_at',
        'notes',
    ];

    protected $casts = [
        'rate_applied' => 'decimal:2',
        'amount' => 'decimal:2',
        'credited_at' => 'datetime',
    ];

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function walletTransaction()
    {
        return $this->belongsTo(WalletTransaction::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RateCard extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'fsc_percent' => 'float',
        'cod_min_charge' => 'float',
        'cod_threshold_amount' => 'float',
        'cod_percent' => 'float',
        'dto_multiplier' => 'float',
        'qc_base_charge' => 'float',
        'qc_included_params' => 'integer',
        'qc_additional_param_charge' => 'float',
        'volumetric_divisor' => 'float',
        'gst_percent' => 'float',
    ];

    public function zones()
    {
        return $this->hasMany(RateCardZone::class);
    }

    /**
     * Calculate COD charge for this rate card according to approved business rules.
     *
     * @param float $codAmount The collected COD cash amount
     * @param bool $isCod Whether the shipment is Cash on Delivery
     * @return float
     */
    public function calculateCodCharge(float $codAmount, bool $isCod = true): float
    {
        if (!$isCod || $codAmount <= 0) {
            return 0.00;
        }

        $minCharge = (float) ($this->cod_min_charge ?? 0.00);
        $percent = (float) ($this->cod_percent ?? 0.00);
        $threshold = (float) ($this->cod_threshold_amount ?? 0.00);

        // Tiered threshold logic:
        if ($threshold > 0) {
            if ($codAmount <= $threshold) {
                return round($minCharge, 2);
            }
            // Above threshold: max(min_charge, cod_amount * percent%)
            $percentCharge = round(($percent / 100) * $codAmount, 2);
            return round(max($minCharge, $percentCharge), 2);
        }

        // Standard logic without threshold: max(min_charge, cod_amount * percent%)
        $percentCharge = round(($percent / 100) * $codAmount, 2);
        return round(max($minCharge, $percentCharge), 2);
    }
}

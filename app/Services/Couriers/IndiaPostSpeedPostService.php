<?php

namespace App\Services\Couriers;

use App\Models\RateCard;

class IndiaPostSpeedPostService implements CourierInterface
{
    protected array $credentials;

    public function __construct(array $credentials = [])
    {
        $this->credentials = $credentials;
    }

    public function checkServiceability(string $pincode): bool
    {
        return true; 
    }

    public function calculateRate(string $pickup_pincode, string $delivery_pincode, float $weight, bool $is_cod = false, float $invoice_value = 0): array
    {
        $zoneIndex = $this->detectZone($pickup_pincode, $delivery_pincode);
        $zoneNames = ['Zone 1 - Local', 'Zone 2 - Regional', 'Zone 3 - Metros', 'Zone 4 - Rest of India'];
        $zoneName = $zoneNames[$zoneIndex];

        $rateCard = RateCard::with('zones')->where('courier_id', 10)->where('is_active', true)->latest()->first();
        
        if (!$rateCard) {
            return ['status' => 'error', 'message' => 'Active rate card not found for India Post Speed Post'];
        }

        $zoneRate = $rateCard->zones->firstWhere('zone_name', $zoneName);
        if (!$zoneRate) {
            return ['status' => 'error', 'message' => "Pricing not available for $zoneName"];
        }

        $baseFreight = $this->calculateFreight($weight, $zoneRate);

        // Calculate GST (treated as FSC in DB)
        $fsc_percent = $rateCard->fsc_percent / 100;
        $gstAmount = round($baseFreight * $fsc_percent, 2);
        
        $codCharge = 0;
        if ($is_cod) {
            $calculatedCod = $invoice_value * ($rateCard->cod_percent / 100);
            $codCharge = round(max($calculatedCod, $rateCard->cod_min_charge), 2);
        }

        $total = $baseFreight + $gstAmount + $codCharge;

        return [
            'status'         => 'success',
            'provider'       => 'India Post Speed Post',
            'zone'           => str_replace('Zone ', '', explode(' - ', $zoneName)[0]),
            'weight_charged' => $weight,
            'base_freight'   => $baseFreight, 
            'fsc'            => $gstAmount,   
            'cod_charge'     => $codCharge, 
            'base_rate'      => $total,
            'total'          => $total,
            'breakdown'      => [
                'Base Freight'          => "₹{$baseFreight}",
                "GST ({$rateCard->fsc_percent}%)" => "₹{$gstAmount}",
                'COD Charge'            => "₹{$codCharge}",
                'Grand Total (pre-margin)' => "₹{$total}",
            ],
        ];
    }

    public function createShipment(array $shipmentDetails): array
    {
        return ['status' => 'error', 'message' => 'Not implemented yet'];
    }

    public function trackShipment(string $awb): array
    {
        return ['status' => 'error', 'message' => 'Not implemented yet'];
    }

    private function calculateFreight(float $weight, $zr): float
    {
        $cw = ceil($weight * 2) / 2;
        if ($cw < 0.5) $cw = 0.5;

        if ($cw <= 0.5) return $zr->first_0_5_kg;
        elseif ($cw <= 1.0) return $zr->first_0_5_kg + $zr->addl_0_5_kg;
        elseif ($cw <= 1.5) return $zr->first_0_5_kg + ($zr->addl_0_5_kg * 2);
        elseif ($cw <= 2.0) return $zr->first_2_kg;
        elseif ($cw <= 3.0) return $zr->first_2_kg + $zr->addl_1_kg_after_2;
        elseif ($cw <= 4.0) return $zr->first_2_kg + ($zr->addl_1_kg_after_2 * 2);
        elseif ($cw <= 5.0) return $zr->first_5_kg;
        else return $zr->first_5_kg + (ceil($cw - 5) * $zr->addl_1_kg_after_5);
    }

    private function detectZone(string $fromPin, string $toPin): int
    {
        $from = trim($fromPin);
        $to   = trim($toPin);

        if ($from === $to) return 0; // Local
        
        $fromPfx3 = substr($from, 0, 3);
        $toPfx3   = substr($to,   0, 3);
        if ($fromPfx3 === $toPfx3) return 0; // Local

        $fromPfx2 = substr($from, 0, 2);
        $toPfx2   = substr($to,   0, 2);
        if ($fromPfx2 === $toPfx2) return 1; // Within State

        $metros = ['11', '40', '56', '60', '70'];
        if (in_array($fromPfx2, $metros) && in_array($toPfx2, $metros)) {
            return 2; // Zone/Metro
        }

        return 3; // Other States
    }
}

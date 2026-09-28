<?php

namespace App\Services\Couriers;

/**
 * XpressbeesService
 *
 * Rate Card Source: Official Xpressbees Rate Card (as uploaded)
 * Margin Applied  : +20% on all base freight rates
 * COD Charges     : Rs.30 + 1.5% of invoice value (cumulative)
 * FSC             : 10% on base freight
 *
 * Zone Definitions (Xpressbees):
 *   Zone1 (Local)      - Same city / same municipal limits
 *   Zone2 (Regional)   - Cluster pin codes within origin region
 *   Zone3 (Metros)     - BOM, DEL, HYD, BLR, MAA, CCU (excl. local/origin metro)
 *   Zone4 (Rest India) - All others excl. NE / J&K / Kerala
 *   Zone5 (NE/J&K/KL)  - Northeast, J&K, and Kerala pin codes
 *   Zone6 (Special)    - Port Blair / Andaman & Nicobar (not quoted)
 */
class XpressbeesService implements CourierInterface
{
    protected array $credentials;

    // ─── MARGIN (20%) ────────────────────────────────────────────────────────
    private const MARGIN   = 1.20;  // 20% added to base freight

    // ─── FSC ────────────────────────────────────────────────────────────────
    private const FSC      = 0.10;  // 10% Fuel Surcharge on base

    // ─── COD ────────────────────────────────────────────────────────────────
    private const COD_FLAT = 30;    // Rs.30 flat
    private const COD_PCT  = 1.5;   // 1.5% of invoice value

    /**
     * Official Xpressbees slab rates (before margin/FSC):
     * Structure: [ max_weight_kg => [z1, z2, z3, z4, z5] ]
     *
     * "Additional per kg" rows are encoded as rate-per-extra-kg
     * applied beyond the previous slab's ceiling.
     *
     * Slabs:
     *  0.5 kg  → First 0.5 kg rates
     *  1.0 kg  → 0.5–1.0 kg (Addl. 0.5 kg applied once)
     *  2.0 kg  → 2 kg flat rates
     *  2–5 kg  → Addl. 1 kg = [20,22,26,28,32] per kg
     *  5.0 kg  → 5 kg flat rates
     *  5–10 kg → Addl. 1 kg = [18,20,22,24,26] per kg
     *  10.0 kg → 10 kg flat rates
     * 10–20 kg → Addl. 1 kg = [16,18,20,22,24] per kg
     *  20.0 kg → 20 kg flat rates
     *   >20 kg → Addl. 1 kg = [14,16,18,20,22] per kg
     */
    private const SLABS = [
        // [ceiling_kg, base_rate [z1,z2,z3,z4,z5], addl_per_extra_kg [z1..z5]]
        // Entry:  up to 0.5 kg
        ['ceil' => 0.5,  'base' => [25, 27, 36, 38, 45],  'addl' => null],
        // Entry:  0.5–1.0 kg (Addl. 0.5 kg once more)
        ['ceil' => 1.0,  'base' => null, 'addl' => [20, 22, 28, 30, 35], 'per' => 0.5],
        // Entry:  up to 2 kg flat
        ['ceil' => 2.0,  'base' => [65, 70, 90, 100, 120], 'addl' => null],
        // Entry:  2–5 kg addl. per kg
        ['ceil' => 5.0,  'base' => null, 'addl' => [20, 22, 26, 28, 32], 'per' => 1],
        // Entry:  5 kg flat
        ['ceil' => 5.0,  'flat' => [120, 140, 160, 180, 200], 'addl' => null],
        // Entry:  5–10 kg addl. per kg
        ['ceil' => 10.0, 'base' => null, 'addl' => [18, 20, 22, 24, 26], 'per' => 1],
        // Entry:  10 kg flat
        ['ceil' => 10.0, 'flat' => [190, 210, 250, 260, 310], 'addl' => null],
        // Entry: 10–20 kg addl. per kg
        ['ceil' => 20.0, 'base' => null, 'addl' => [16, 18, 20, 22, 24], 'per' => 1],
        // Entry:  20 kg flat
        ['ceil' => 20.0, 'flat' => [350, 390, 450, 480, 550], 'addl' => null],
    ];

    // ─── Zone map: rough pincode prefix → zone index (0-based) ──────────────
    // In production this should query the serviceable_pincodes table.
    // This is a best-effort static mapping for the rate calculator.
    private const ZONE_PREFIXES = [
        // Zone5 – NE / J&K / Kerala
        '79' => 4, '78' => 4, '77' => 4, '67' => 4, '68' => 4, '69' => 4,
        '18' => 4, '19' => 4,
        // Zone3 – Metros (Mumbai 4xx, Delhi 11x, Hyderabad 50x, Bangalore 56x, Chennai 60x, Kolkata 70x)
        '40' => 2, '41' => 2,
        '11' => 2,
        '50' => 2,
        '56' => 2,
        '60' => 2,
        '70' => 2,
        // Default → Zone4 (Rest of India)
    ];

    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    public function checkServiceability(string $pincode): bool
    {
        // Zone6 (Andaman) not serviceable via standard Xpressbees
        $prefix = substr(trim($pincode), 0, 2);
        if ($prefix === '74') return false; // Andaman prefix
        return true;
    }

    /**
     * Calculate the Xpressbees rate for a given weight and zone.
     *
     * @param  string $pickup_pincode
     * @param  string $delivery_pincode
     * @param  float  $weight_kg        Chargeable weight (physical or volumetric, whichever higher)
     * @param  bool   $is_cod           Whether the shipment is Cash on Delivery
     * @param  float  $invoice_value    Invoice / declared value (for COD charges)
     * @return array
     */
    public function calculateRate(
        string $pickup_pincode,
        string $delivery_pincode,
        float  $weight = 0.5,
        bool   $is_cod = false,
        float  $invoice_value = 0
    ): array {
        $zoneIndex = $this->detectZone($pickup_pincode, $delivery_pincode);
        $baseFreight = $this->calculateFreight($weight, $zoneIndex);

        // FSC (10% on base freight)
        $fsc = round($baseFreight * self::FSC, 2);

        // Sub-total before COD
        $subtotal = round(($baseFreight + $fsc) * self::MARGIN, 2);

        // COD surcharge: Rs.30 FLAT + 1.5% of invoice value
        $codCharge = 0;
        if ($is_cod && $invoice_value > 0) {
            $codCharge = round(self::COD_FLAT + ($invoice_value * self::COD_PCT / 100), 2);
        }

        $total = round($subtotal + $codCharge, 2);

        return [
            'status'         => 'success',
            'provider'       => 'Xpressbees',
            'zone'           => 'Zone' . ($zoneIndex + 1),
            'weight_charged' => $weight,
            'base_freight'   => $baseFreight,
            'fsc'            => $fsc,
            'margin_pct'     => '20%',
            'subtotal'       => $subtotal,
            'cod_charge'     => $codCharge,
            'cod_formula'    => 'Rs.30 + 1.5% of invoice value',
            'base_rate'      => $total,
            'total'          => $total,
            'breakdown'      => [
                'Base Freight'  => "₹{$baseFreight}",
                'FSC (10%)'     => "₹{$fsc}",
                'Margin (20%)'  => 'Included',
                'COD Charge'    => $is_cod ? "₹{$codCharge} (₹30 + 1.5%)" : 'N/A',
                'Grand Total'   => "₹{$total}",
            ],
        ];
    }

    public function createShipment(array $shipmentDetails): array
    {
        return []; // Implement when Xpressbees API credentials are available
    }

    // ─── Internal Helpers ────────────────────────────────────────────────────

    /**
     * Calculate base freight from official rate card slabs.
     */
    private function calculateFreight(float $weight, int $zoneIndex): float
    {
        // Ceiling to 0.5 kg slabs (Xpressbees bills per 0.5 kg)
        $chargeableWeight = ceil($weight * 2) / 2;
        if ($chargeableWeight < 0.5) $chargeableWeight = 0.5;

        if ($chargeableWeight <= 0.5) {
            // First 0.5 kg
            return [25, 27, 36, 38, 45][$zoneIndex];
        }

        if ($chargeableWeight <= 1.0) {
            // First 0.5 kg + one Addl. 0.5 kg
            return [25, 27, 36, 38, 45][$zoneIndex]
                 + [20, 22, 28, 30, 35][$zoneIndex];
        }

        if ($chargeableWeight <= 2.0) {
            // Use 2 kg flat slab
            return [65, 70, 90, 100, 120][$zoneIndex];
        }

        if ($chargeableWeight <= 5.0) {
            // 2 kg flat + addl. per kg beyond 2 kg
            $base  = [65, 70, 90, 100, 120][$zoneIndex];
            $extra = ceil($chargeableWeight - 2.0);     // whole kg units
            return $base + $extra * [20, 22, 26, 28, 32][$zoneIndex];
        }

        if ($chargeableWeight <= 10.0) {
            // 5 kg flat + addl. per kg beyond 5 kg
            $base  = [120, 140, 160, 180, 200][$zoneIndex];
            $extra = ceil($chargeableWeight - 5.0);
            return $base + $extra * [18, 20, 22, 24, 26][$zoneIndex];
        }

        if ($chargeableWeight <= 20.0) {
            // 10 kg flat + addl. per kg beyond 10 kg
            $base  = [190, 210, 250, 260, 310][$zoneIndex];
            $extra = ceil($chargeableWeight - 10.0);
            return $base + $extra * [16, 18, 20, 22, 24][$zoneIndex];
        }

        // > 20 kg: 20 kg flat + addl. per kg
        $base  = [350, 390, 450, 480, 550][$zoneIndex];
        $extra = ceil($chargeableWeight - 20.0);
        return $base + $extra * [14, 16, 18, 20, 22][$zoneIndex];
    }

    /**
     * Detect zone index (0 = Zone1 … 4 = Zone5) from pincodes.
     * Same city → Zone1 | Same prefix2 → Zone2 | Metro prefixes → Zone3
     * NE/J&K/KL → Zone5 | Everything else → Zone4
     */
    private function detectZone(string $fromPin, string $toPin): int
    {
        $from = trim($fromPin);
        $to   = trim($toPin);

        // Zone1 – same pincode (local)
        if ($from === $to) return 0;

        $fromPfx2 = substr($from, 0, 2);
        $toPfx2   = substr($to,   0, 2);

        // Zone5 – NE / J&K / Kerala delivery
        $zone5 = ['79', '78', '77', '67', '68', '69', '18', '19'];
        if (in_array($toPfx2, $zone5)) return 4;

        // Zone3 – Metro cities
        $metros = ['40', '41', '11', '50', '56', '60', '70'];
        if (in_array($toPfx2, $metros) && !in_array($fromPfx2, ['40', '41', '11', '50', '56', '60', '70'])) return 2;

        // Zone2 – same first-2-digit prefix (regional cluster)
        if ($fromPfx2 === $toPfx2) return 1;

        // Zone4 – Rest of India
        return 3;
    }
}

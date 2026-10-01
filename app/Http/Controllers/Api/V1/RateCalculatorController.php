<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Services\PricingService;
use Illuminate\Http\Request;

class RateCalculatorController extends Controller
{
    protected $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    public function calculate(Request $request)
    {
        $shipmentType = strtolower($request->input('shipment_type', 'domestic'));
        if ($shipmentType === 'international' || $request->filled('destination_country')) {
            $request->validate([
                'destination_country' => 'required|string',
                'boxes' => 'nullable|array',
                'boxes.*.no_of_boxes' => 'nullable|numeric|min:1',
                'boxes.*.length' => 'nullable|numeric|min:1',
                'boxes.*.width' => 'nullable|numeric|min:1',
                'boxes.*.height' => 'nullable|numeric|min:1',
                'boxes.*.weight' => 'nullable|numeric|min:0.01',
            ]);
            return $this->calculateInternational($request);
        }

        $request->validate([
            'pickup_pincode' => 'required|digits:6',
            'delivery_pincode' => 'required|digits:6',
            'weight' => 'nullable|numeric|min:0.01',
            'payment_mode' => 'nullable|string',
            'risk_type' => 'nullable|string',
            'invoice_amount' => 'nullable|numeric|min:0',
            'cod_amount' => 'nullable|numeric|min:0',
            'shipment_value' => 'nullable|numeric|min:0',
            'boxes' => 'nullable|array',
            'boxes.*.no_of_boxes' => 'nullable|numeric|min:1',
            'boxes.*.length' => 'nullable|numeric|min:1',
            'boxes.*.width' => 'nullable|numeric|min:1',
            'boxes.*.height' => 'nullable|numeric|min:1',
            'boxes.*.weight' => 'nullable|numeric|min:0.01',
        ]);

        // Fetch dynamic admin charges
        $dynamicSettings = \App\Models\Setting::where('group', 'rate_charges')->get();
        $adminDynamicCharges = [];
        $adminDynamicTotal = 0.00;
        foreach($dynamicSettings as $ds) {
            $val = floatval($ds->value);
            if ($val > 0) {
                $adminDynamicCharges[] = [
                    'name' => $ds->key,
                    'amount' => round($val, 2)
                ];
                $adminDynamicTotal += $val;
            }
        }

        $pickupPin = $request->pickup_pincode;
        $deliveryPin = $request->delivery_pincode;
        $paymentMode = strtolower($request->input('payment_mode', 'prepaid'));
        $riskType = strtolower($request->input('risk_type', 'owner_risk'));
        $invoiceAmount = floatval($request->input('invoice_amount') ?? $request->input('shipment_value') ?? 0);
        $isCod = ($paymentMode === 'cod');
        $codAmount = $isCod ? floatval($request->input('cod_amount') ?: $invoiceAmount) : 0;
        $isToPay = ($paymentMode === 'topay');

        // Multi-box calculations
        $boxes = $request->input('boxes', []);
        $totalActualWeight = 0;
        $totalVolumetricWeight = 0;
        $totalChargeableWeight = 0;

        if (!empty($boxes) && is_array($boxes)) {
            foreach ($boxes as $box) {
                $count = max(1, intval($box['no_of_boxes'] ?? 1));
                $l = floatval($box['length'] ?? 10);
                $w = floatval($box['width'] ?? 10);
                $h = floatval($box['height'] ?? 10);
                $wt = floatval($box['weight'] ?? 0.5);

                $volWt = ($l * $w * $h) / 5000;
                $boxChargeable = max($wt, $volWt);

                $totalActualWeight += ($wt * $count);
                $totalVolumetricWeight += ($volWt * $count);
                $totalChargeableWeight += ($boxChargeable * $count);
            }
        } else {
            $wt = floatval($request->input('weight') ?: 0.5);
            $l = floatval($request->input('dimensions.l') ?: 10);
            $w = floatval($request->input('dimensions.w') ?: 10);
            $h = floatval($request->input('dimensions.h') ?: 10);
            $volWt = ($l * $w * $h) / 5000;
            $totalActualWeight = $wt;
            $totalVolumetricWeight = $volWt;
            $totalChargeableWeight = max($wt, $volWt);
        }

        $totalChargeableWeight = round(max(0.1, $totalChargeableWeight), 3);

        // Determine Region / Zone text
        $zoneCode = 'Zone B';
        $tatZone = 'N2-N1';
        if (substr($pickupPin, 0, 2) === substr($deliveryPin, 0, 2)) {
            $zoneCode = 'Zone A (Intra-City)';
            $tatZone = 'City';
        } elseif (substr($pickupPin, 0, 1) === substr($deliveryPin, 0, 1)) {
            $zoneCode = 'Zone B (Regional)';
            $tatZone = 'North-North';
        } elseif (in_array(substr($deliveryPin, 0, 2), ['11', '12', '13', '14', '15', '16', '20', '22', '24', '25', '28'])) {
            $zoneCode = 'Zone C (Metro)';
            $tatZone = 'Metro-North';
        } else {
            $zoneCode = 'Zone D (National / ROI)';
            $tatZone = 'North-ROI';
        }

        // Check OneStall Direct Serviceability for Delivery Pincode
        $routing = app(\App\Services\ServiceabilityService::class)->determineRouting($deliveryPin);
        $isOneStallServiceable = ($routing['fulfillment_type'] === 'onestall');

        // Fetch active couriers based on OneStall availability
        if ($isOneStallServiceable) {
            // Show ONLY OneStall if OneStall covers this route
            $activeCouriers = Courier::where('is_active', true)
                ->where(function($q) {
                    $q->where('name', 'LIKE', '%OneStall%')
                      ->orWhere('name', 'LIKE', '%Onestall%');
                })->get();

            // If OneStall courier record not in couriers table, create virtual OneStall courier
            if ($activeCouriers->isEmpty()) {
                $activeCouriers = collect([
                    (object) [
                        'id' => 999,
                        'name' => 'OneStall Cargo (Direct)',
                        'mode' => 'production',
                        'is_active' => true,
                        'markup_type' => 'percentage',
                        'markup_value' => 0
                    ]
                ]);
            }
        } else {
            // If OneStall is NOT available for this route, show all other 3rd party active couriers
            $activeCouriers = Courier::where('is_active', true)
                ->where('name', 'NOT LIKE', '%OneStall%')
                ->where('name', 'NOT LIKE', '%Onestall%')
                ->get();
        }

        if ($activeCouriers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No active courier partners available for this route.',
                'data' => []
            ], 404);
        }

        $ratesList = [];

        foreach ($activeCouriers as $courier) {
            try {
                // Calculate rate for this courier
                if (stripos($courier->name, 'OneStall') !== false) {
                    $rateRes = $this->pricingService->calculateOneStallRate(
                        $pickupPin, $deliveryPin, $totalChargeableWeight, 10, 10, 10, $isCod, ($isCod && $codAmount > 0 ? $codAmount : $invoiceAmount)
                    );
                    $baseCharge = $rateRes['base_freight'] ?? 45.00;
                    $fscAmount = $rateRes['fsc_amount'] ?? round($baseCharge * 0.10, 2);
                } else {
                    $rateRes = $this->pricingService->calculateExternalRate(
                        $courier->id, $pickupPin, $deliveryPin, $totalChargeableWeight, $isCod, $invoiceAmount
                    );
                    $baseCharge = $rateRes['base_freight'] ?? 50.00;
                    $fscAmount = $rateRes['fsc_amount'] ?? round($baseCharge * 0.12, 2);
                }

                // Add-on components (Dynamic + Calculated)
                $waraiCharge = $fscAmount; // Warai / FSC Fuel Surcharge
                $toPayCharge = $isToPay ? 50.00 : 0.00;
                
                // COD charge
                $codCharge = 0.00;
                if ($isCod) {
                    $codCharge = max(40.00, round($codAmount * 0.02, 2));
                }

                // Insurance (Only if selected and invoice amount specified)
                $insuranceCharge = 0.00;
                if ($riskType === 'third_party_insurance' && $invoiceAmount > 0) {
                    $insuranceCharge = round($invoiceAmount * 0.01, 2);
                } elseif ($riskType === 'carrier_risk' && $invoiceAmount > 0) {
                    $insuranceCharge = round($invoiceAmount * 0.02, 2);
                }

                // Subtotal for Tax
                $subtotal = $baseCharge + $waraiCharge + $toPayCharge + $insuranceCharge + $codCharge + $adminDynamicTotal;
                $stateTax = round($subtotal * 0.18, 2); // 18% GST

                $finalTotal = round($subtotal + $stateTax, 2);

                // Estimated TAT (Turn Around Time)
                $tat = '3';
                if (stripos($courier->name, 'Blue Dart') !== false) {
                    $tat = '2';
                } elseif (stripos($courier->name, 'Express') !== false || stripos($courier->name, 'Shadowfax') !== false) {
                    $tat = '3';
                } elseif (stripos($courier->name, 'DTDC') !== false || stripos($courier->name, 'Ekart') !== false) {
                    $tat = '4';
                } else {
                    $tat = '3';
                }

                $ratesList[] = [
                    'courier_id' => $courier->id,
                    'courier_name' => $courier->name,
                    'mode' => $courier->mode,
                    'tat' => $tat,
                    'tat_zone' => $tatZone,
                    'zone' => $zoneCode,
                    'chargeable_weight' => $totalChargeableWeight,
                    'actual_weight' => round($totalActualWeight, 2),
                    'volumetric_weight' => round($totalVolumetricWeight, 2),
                    'base_charge' => round($baseCharge, 2),
                    'dynamic_charges' => $adminDynamicCharges,
                    'warai_charge' => round($waraiCharge, 2),
                    'to_pay' => round($toPayCharge, 2),
                    'state_tax' => round($stateTax, 2),
                    'third_party_insurance' => round($insuranceCharge, 2),
                    'cod_charges' => round($codCharge, 2),
                    'total_rate' => $finalTotal,
                    'rate' => $finalTotal // For backward compatibility
                ];
            } catch (\Exception $e) {
                // Fallback estimate for active courier so user sees all active options
                $baseCharge = 65.00 * ($totalChargeableWeight > 0.5 ? ceil($totalChargeableWeight / 0.5) * 0.65 : 1);
                $fscAmount = round($baseCharge * 0.12, 2);
                $codCharge = $isCod ? max(40.00, round($codAmount * 0.02, 2)) : 0.00;
                $toPayCharge = $isToPay ? 50.00 : 0.00;
                $insuranceCharge = ($riskType === 'third_party_insurance' && $invoiceAmount > 0) ? round($invoiceAmount * 0.01, 2) : 0.00;
                $subtotal = $baseCharge + $fscAmount + $codCharge + $toPayCharge + $insuranceCharge + $adminDynamicTotal;
                $stateTax = round($subtotal * 0.18, 2);
                $finalTotal = round($subtotal + $stateTax, 2);

                $ratesList[] = [
                    'courier_id' => $courier->id,
                    'courier_name' => $courier->name,
                    'mode' => $courier->mode,
                    'tat' => '3',
                    'tat_zone' => $tatZone,
                    'zone' => $zoneCode,
                    'chargeable_weight' => $totalChargeableWeight,
                    'actual_weight' => round($totalActualWeight, 2),
                    'volumetric_weight' => round($totalVolumetricWeight, 2),
                    'base_charge' => round($baseCharge, 2),
                    'dynamic_charges' => $adminDynamicCharges,
                    'warai_charge' => round($fscAmount, 2),
                    'to_pay' => round($toPayCharge, 2),
                    'state_tax' => round($stateTax, 2),
                    'third_party_insurance' => round($insuranceCharge, 2),
                    'cod_charges' => round($codCharge, 2),
                    'total_rate' => $finalTotal,
                    'rate' => $finalTotal
                ];
            }
        }

        // Sort by total_rate ascending (cheapest first)
        usort($ratesList, function ($a, $b) {
            return $a['total_rate'] <=> $b['total_rate'];
        });

        return response()->json([
            'success' => true,
            'message' => 'Rates calculated successfully',
            'chargeable_weight' => $totalChargeableWeight,
            'actual_weight' => $totalActualWeight,
            'volumetric_weight' => $totalVolumetricWeight,
            'data' => $ratesList
        ]);
    }

    /**
     * Calculate International Courier Shipping Rates
     */
    private function calculateInternational(Request $request)
    {
        // Fetch dynamic admin charges
        $dynamicSettings = \App\Models\Setting::where('group', 'rate_charges')->get();
        $adminDynamicCharges = [];
        $adminDynamicTotal = 0.00;
        foreach($dynamicSettings as $ds) {
            $val = floatval($ds->value);
            if ($val > 0) {
                $adminDynamicCharges[] = [
                    'name' => $ds->key,
                    'amount' => round($val, 2)
                ];
                $adminDynamicTotal += $val;
            }
        }

        $pickupCountry = $request->input('pickup_country', 'India');
        $destCountry = $request->input('destination_country', 'United States');
        $serviceType = $request->input('shipment_service_type', 'World Wide Parcel');
        $shipmentCategory = $request->input('shipment_category', 'Cargo Shipment');
        $shipmentSubCategory = $request->input('shipment_sub_category', 'General Goods');
        $invoiceAmount = floatval($request->input('invoice_amount', 1000));

        // Multi-box calculations
        $boxes = $request->input('boxes', []);
        $totalActualWeight = 0;
        $totalVolumetricWeight = 0;
        $totalChargeableWeight = 0;

        if (!empty($boxes) && is_array($boxes)) {
            foreach ($boxes as $box) {
                $count = max(1, intval($box['no_of_boxes'] ?? 1));
                $l = floatval($box['length'] ?? 10);
                $w = floatval($box['width'] ?? 10);
                $h = floatval($box['height'] ?? 10);
                $wt = floatval($box['weight'] ?? 0.5);

                $volWt = ($l * $w * $h) / 5000;
                $boxChargeable = max($wt, $volWt);

                $totalActualWeight += ($wt * $count);
                $totalVolumetricWeight += ($volWt * $count);
                $totalChargeableWeight += ($boxChargeable * $count);
            }
        } else {
            $wt = floatval($request->input('weight') ?: 0.5);
            $totalActualWeight = $wt;
            $totalVolumetricWeight = $wt;
            $totalChargeableWeight = $wt;
        }

        $totalChargeableWeight = round(max(0.5, $totalChargeableWeight), 3);

        // International Zone Detection based on destination country
        $destLower = strtolower($destCountry);
        $zone = 'Zone 4 (North America)';
        $zoneMultiplier = 1.0;
        $tatDays = '4-6';

        if (str_contains($destLower, 'emirates') || str_contains($destLower, 'dubai') || str_contains($destLower, 'uae') || str_contains($destLower, 'saudi') || str_contains($destLower, 'qatar') || str_contains($destLower, 'oman') || str_contains($destLower, 'kuwait') || str_contains($destLower, 'bahrain')) {
            $zone = 'Zone 1 (Middle East / Gulf)';
            $zoneMultiplier = 0.65;
            $tatDays = '3-4';
        } elseif (str_contains($destLower, 'singapore') || str_contains($destLower, 'malaysia') || str_contains($destLower, 'thailand') || str_contains($destLower, 'japan') || str_contains($destLower, 'hong kong')) {
            $zone = 'Zone 2 (SE Asia & Far East)';
            $zoneMultiplier = 0.80;
            $tatDays = '3-5';
        } elseif (str_contains($destLower, 'united kingdom') || str_contains($destLower, 'uk') || str_contains($destLower, 'germany') || str_contains($destLower, 'france') || str_contains($destLower, 'italy') || str_contains($destLower, 'netherlands') || str_contains($destLower, 'spain')) {
            $zone = 'Zone 3 (Europe & UK)';
            $zoneMultiplier = 0.90;
            $tatDays = '3-5';
        } elseif (str_contains($destLower, 'australia') || str_contains($destLower, 'new zealand') || str_contains($destLower, 'south africa')) {
            $zone = 'Zone 5 (Oceania & Africa)';
            $zoneMultiplier = 1.15;
            $tatDays = '4-7';
        } else {
            $zone = 'Zone 4 (North America & RoW)';
            $zoneMultiplier = 1.0;
            $tatDays = '4-6';
        }

        // Additional weight slabs (first 0.5kg + each addl 0.5kg)
        $slabs = ceil($totalChargeableWeight / 0.5);
        $addlSlabs = max(0, $slabs - 1);

        $couriers = [
            [
                'name' => 'OneStall Global Priority',
                'mode' => 'Air Express Direct',
                'base_first' => 950 * $zoneMultiplier,
                'base_addl' => 240 * $zoneMultiplier,
                'fsc_pct' => 0.10,
                'customs' => 200.00,
                'handling' => 100.00,
                'tat' => $tatDays . ' Days',
                'recommended' => true
            ],
            [
                'name' => 'DHL Express Worldwide',
                'mode' => 'Air Priority Premium',
                'base_first' => 1250 * $zoneMultiplier,
                'base_addl' => 310 * $zoneMultiplier,
                'fsc_pct' => 0.15,
                'customs' => 300.00,
                'handling' => 150.00,
                'tat' => '2-4 Days',
                'recommended' => false
            ],
            [
                'name' => 'FedEx International Priority',
                'mode' => 'Air Express',
                'base_first' => 1180 * $zoneMultiplier,
                'base_addl' => 290 * $zoneMultiplier,
                'fsc_pct' => 0.14,
                'customs' => 280.00,
                'handling' => 120.00,
                'tat' => '3-5 Days',
                'recommended' => false
            ],
            [
                'name' => 'Aramex International Parcel',
                'mode' => 'Economy Air',
                'base_first' => 850 * $zoneMultiplier,
                'base_addl' => 220 * $zoneMultiplier,
                'fsc_pct' => 0.10,
                'customs' => 180.00,
                'handling' => 80.00,
                'tat' => '4-6 Days',
                'recommended' => false
            ],
            [
                'name' => 'UPS Worldwide Saver',
                'mode' => 'Air Express',
                'base_first' => 1120 * $zoneMultiplier,
                'base_addl' => 280 * $zoneMultiplier,
                'fsc_pct' => 0.12,
                'customs' => 250.00,
                'handling' => 110.00,
                'tat' => '3-5 Days',
                'recommended' => false
            ],
        ];

        $ratesList = [];

        foreach ($couriers as $idx => $c) {
            $baseFreight = round($c['base_first'] + ($addlSlabs * $c['base_addl']), 2);
            $fscCharge = round($baseFreight * $c['fsc_pct'], 2);
            $customsFee = $c['customs'];

            $subtotal = $baseFreight + $fscCharge + $customsFee + $adminDynamicTotal;
            $tax = round($subtotal * 0.18, 2);
            $finalTotal = round($subtotal + $tax, 2);

            $ratesList[] = [
                'courier_id' => 100 + $idx,
                'courier_name' => $c['name'],
                'mode' => $c['mode'],
                'tat' => $c['tat'],
                'tat_zone' => $zone,
                'zone' => $zone,
                'chargeable_weight' => $totalChargeableWeight,
                'actual_weight' => round($totalActualWeight, 2),
                'volumetric_weight' => round($totalVolumetricWeight, 2),
                'base_charge' => $baseFreight,
                'dynamic_charges' => $adminDynamicCharges,
                'warai_charge' => $fscCharge,
                'customs_clearance' => $customsFee,
                'state_tax' => $tax,
                'total_rate' => $finalTotal,
                'rate' => $finalTotal,
                'recommended' => $c['recommended']
            ];
        }

        // Sort by total_rate ascending
        usort($ratesList, function ($a, $b) {
            return $a['total_rate'] <=> $b['total_rate'];
        });

        return response()->json([
            'success' => true,
            'message' => 'International rates calculated successfully',
            'chargeable_weight' => $totalChargeableWeight,
            'actual_weight' => $totalActualWeight,
            'volumetric_weight' => $totalVolumetricWeight,
            'pickup_country' => $pickupCountry,
            'destination_country' => $destCountry,
            'data' => $ratesList
        ]);
    }
}


<?php

namespace App\Services\Couriers;

use App\Models\RateCard;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

/**
 * XpressbeesService
 *
 * Margin Applied  : +20% on all base freight rates
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

    // ─── Zone map: rough pincode prefix → zone index (0-based) ──────────────
    private const ZONE_PREFIXES = [
        // Zone5 – NE / J&K / Kerala
        '79' => 4, '78' => 4, '77' => 4, '67' => 4, '68' => 4, '69' => 4,
        '18' => 4, '19' => 4,
        // Zone3 – Metros
        '40' => 2, '41' => 2,
        '11' => 2,
        '50' => 2,
        '56' => 2,
        '60' => 2,
        '70' => 2,
    ];

    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }



    public function calculateRate(
        string $pickup_pincode,
        string $delivery_pincode,
        float  $weight = 0.5,
        bool   $is_cod = false,
        float  $invoice_value = 0
    ): array {
        $zoneIndex = $this->detectZone($pickup_pincode, $delivery_pincode);
        $zoneName = 'Zone ' . ($zoneIndex + 1);

        // Fetch dynamic RateCard for Xpressbees (Courier ID 2)
        $rateCard = RateCard::with('zones')->where('courier_id', 2)->where('is_active', true)->latest()->first();
        
        if (!$rateCard) {
            return ['status' => 'error', 'message' => 'Active rate card not found for Xpressbees'];
        }

        $zoneRate = $rateCard->zones->firstWhere('zone_name', $zoneName);
        if (!$zoneRate) {
            return ['status' => 'error', 'message' => "Pricing not available for $zoneName"];
        }

        $rawBaseFreight = $this->calculateFreight($weight, $zoneRate);

        // FSC (dynamic from rate card)
        $fsc_percent = $rateCard->fsc_percent / 100;
        $rawFsc = round($rawBaseFreight * $fsc_percent, 2);

        // Apply Margin separately so PricingService can add them without double counting
        $baseFreight = round($rawBaseFreight * self::MARGIN, 2);
        $fsc = round($rawFsc * self::MARGIN, 2);

        // Sub-total before COD
        $subtotal = round($baseFreight + $fsc, 2);

        // COD surcharge (dynamic from rate card)
        $codCharge = 0;
        if ($is_cod) {
            $calcCod = $invoice_value > 0 ? ($invoice_value * $rateCard->cod_percent / 100) : 0;
            $codCharge = round(max($rateCard->cod_min_charge, $calcCod), 2);
        }

        $total = round($subtotal + $codCharge, 2);

        return [
            'status'         => 'success',
            'provider'       => 'Xpressbees',
            'zone'           => $zoneName,
            'weight_charged' => $weight,
            'base_freight'   => $baseFreight, // Now includes 20% margin
            'fsc'            => $fsc,         // Now includes 20% margin
            'margin_pct'     => '20%',
            'subtotal'       => $subtotal,
            'cod_charge'     => $codCharge,
            'cod_formula'    => "Max(₹{$rateCard->cod_min_charge}, {$rateCard->cod_percent}% of invoice value)",
            'base_rate'      => $total,
            'total'          => $total,
            'breakdown'      => [
                'Raw Base Freight' => "₹{$rawBaseFreight}",
                'Raw FSC'          => "₹{$rawFsc}",
                'Margin (20%)'     => 'Applied to Base & FSC',
                'COD Charge'       => $is_cod ? "₹{$codCharge}" : 'N/A',
                'Grand Total'      => "₹{$total}",
            ],
        ];
    }

    /**
     * Generate or Retrieve Cached Xpressbees Auth Token
     */
    private function getToken(): string
    {
        return Cache::remember('xb_token_' . md5($this->credentials['username']), 86000, function () {
            $tokenEndpoint = $this->credentials['endpoints']['token'] ?? 'https://userauthapis.xbees.in/api/auth/generateToken';
            
            $payload = [
                'username' => $this->credentials['username'],
                'password' => $this->credentials['password'],
                'secretkey' => $this->credentials['secret_key']
            ];

            $response = Http::post($tokenEndpoint, $payload);
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['data'] ?? $data['Token'] ?? $data['token'] ?? '';
            }

            throw new \Exception("Failed to generate Xpressbees token: " . $response->body());
        });
    }

    public function checkServiceability(string $pincode): bool
    {
        try {
            $url = $this->credentials['endpoints']['serviceability'] ?? 'https://xbmasterapi.xbees.in/expose/get/serviceabilitypincode/details';
            $token = $this->getToken();
            
            $response = Http::withHeaders([
                'Token' => $token,
                'XBkey' => $this->credentials['xb_key'] ?? ''
            ])->post($url, [
                'pinCode' => $pincode
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['status']) && $data['status'] === true) {
                    return true;
                }
            }
        } catch (\Exception $e) {
            // Fallback
        }
        
        $prefix = substr(trim($pincode), 0, 2);
        if ($prefix === '74') return false; 
        return true;
    }

    public function createShipment(array $shipmentDetails): array
    {
        try {
            $token = $this->getToken();
            $headers = [
                'Token' => $token,
                'XBkey' => $this->credentials['xb_key'] ?? ''
            ];

            // Step 1: Generate AWB
            $awbUrl = $this->credentials['endpoints']['awb_series'] ?? 'https://xbclientapi.xbees.in/POSTShipmentService.svc/AWBNumberSeriesGeneration';
            $awbResponse = Http::withHeaders($headers)->post($awbUrl, [
                'ClientName' => $this->credentials['client_name'] ?? 'Onestall'
            ]);

            if (!$awbResponse->successful()) {
                return ['status' => 'error', 'message' => 'Failed to generate AWB: ' . $awbResponse->body()];
            }
            
            $awbData = $awbResponse->json();
            // Just for debugging what Xpressbees returns
            if (!isset($awbData['AWBNo']) && !isset($awbData['data'])) {
                return ['status' => 'error', 'message' => 'AWB Gen failed, got: ' . json_encode($awbData)];
            }
            $awbNumber = $awbData['AWBNo'] ?? $awbData['data'] ?? ''; // Adjust based on actual response

            // Step 2: Manifest Forward
            $manifestUrl = $this->credentials['endpoints']['manifest'] ?? 'https://apishipmentmanifestation.xbees.in/shipmentmanifestation/forward';
            $manifestPayload = [
                'AWBNo' => $awbNumber,
                'OrderNo' => $shipmentDetails['order_id'] ?? uniqid(),
                'ConsigneeName' => $shipmentDetails['receiver_name'],
                'ConsigneePhone' => $shipmentDetails['receiver_phone'],
                'ConsigneeAddress' => $shipmentDetails['delivery_address'],
                'ConsigneePinCode' => $shipmentDetails['delivery_pincode'],
                'ConsigneeCity' => $shipmentDetails['delivery_city'],
                'ConsigneeState' => $shipmentDetails['delivery_state'] ?? 'State',
                'PaymentType' => ($shipmentDetails['is_cod'] ?? false) ? 'COD' : 'Prepaid',
                'CollectableAmount' => ($shipmentDetails['is_cod'] ?? false) ? ($shipmentDetails['invoice_value'] ?? 0) : 0,
                'DeclaredValue' => $shipmentDetails['invoice_value'] ?? 0,
                'Weight' => $shipmentDetails['weight_kg'] ?? 0.5,
                'Length' => $shipmentDetails['length_cm'] ?? 10,
                'Breadth' => $shipmentDetails['width_cm'] ?? 10,
                'Height' => $shipmentDetails['height_cm'] ?? 10,
                'PickupName' => $shipmentDetails['pickup_name'] ?? 'OneStall Cargo Hub',
                'PickupPhone' => $shipmentDetails['pickup_phone'] ?? '9999999999',
                'PickupAddress' => $shipmentDetails['pickup_address'] ?? 'Warehouse',
                'PickupPinCode' => $shipmentDetails['pickup_pincode'] ?? '',
                'PickupCity' => $shipmentDetails['pickup_city'] ?? '',
                'PickupState' => $shipmentDetails['pickup_state'] ?? 'State',
                'ClientName' => $this->credentials['client_name'] ?? 'Onestall',
            ];

            $manifestResponse = Http::withHeaders($headers)->post($manifestUrl, [$manifestPayload]); // Sometimes wrapped in array

            if ($manifestResponse->successful()) {
                return [
                    'status' => 'success',
                    'awb' => $awbNumber,
                    'provider_response' => $manifestResponse->json()
                ];
            }

            return ['status' => 'error', 'message' => 'Manifest failed: ' . $manifestResponse->body()];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Exception during Xpressbees shipment creation: ' . $e->getMessage()];
        }
    }

    public function trackShipment(string $awb): array
    {
        try {
            $token = $this->getToken();
            $trackUrl = $this->credentials['endpoints']['tracking_status'] ?? 'https://apishipmenttracking.xbees.in/GetCurrentShipmentStatus';
            
            $response = Http::withHeaders([
                'Token' => $token,
                'XBkey' => $this->credentials['xb_key'] ?? ''
            ])->post($trackUrl, [
                'AWBNo' => $awb
            ]);

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'tracking_data' => $response->json()
                ];
            }
            return ['status' => 'error', 'message' => 'Tracking failed: ' . $response->body()];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Exception during tracking: ' . $e->getMessage()];
        }
    }

    private function calculateFreight(float $weight, $zr): float
    {
        $cw = ceil($weight * 2) / 2;
        if ($cw < 0.5) $cw = 0.5;

        if ($cw <= 0.5) return $zr->first_0_5_kg;
        elseif ($cw <= 1.0) return $zr->first_0_5_kg + $zr->addl_0_5_kg;
        elseif ($cw <= 2.0) return $zr->first_2_kg;
        elseif ($cw <= 5.0) return $zr->first_2_kg + (ceil($cw - 2) * $zr->addl_1_kg_after_2);
        elseif ($cw <= 10.0) return $zr->first_5_kg + (ceil($cw - 5) * $zr->addl_1_kg_after_5);
        elseif ($cw <= 20.0) return $zr->first_10_kg + (ceil($cw - 10) * $zr->addl_1_kg_after_10);
        else return $zr->first_20_kg + (ceil($cw - 20) * $zr->addl_1_kg_after_20);
    }

    private function detectZone(string $fromPin, string $toPin): int
    {
        $from = trim($fromPin);
        $to   = trim($toPin);

        if ($from === $to) return 0;

        $fromPfx2 = substr($from, 0, 2);
        $toPfx2   = substr($to,   0, 2);

        $zone5 = ['79', '78', '77', '67', '68', '69', '18', '19'];
        if (in_array($toPfx2, $zone5)) return 4;

        $metros = ['40', '41', '11', '50', '56', '60', '70'];
        if (in_array($toPfx2, $metros) && !in_array($fromPfx2, ['40', '41', '11', '50', '56', '60', '70'])) return 2;

        if ($fromPfx2 === $toPfx2) return 1;

        return 3;
    }
}

<?php

namespace App\Services;

use App\Models\Shipment;
use App\Models\Franchise;
use App\Models\Courier;
use Illuminate\Support\Facades\Log;

class ShipmentService
{
    protected $awbService;
    protected $pricingService;

    public function __construct(AwbGeneratorService $awbService, PricingService $pricingService)
    {
        $this->awbService = $awbService;
        $this->pricingService = $pricingService;
    }

    /**
     * Determine if a pincode is covered by any active franchise.
     * Returns Franchise ID if found, otherwise null.
     */
    public function getFranchiseForPincode(string $pincode): ?int
    {
        // Get active franchises
        $franchises = Franchise::where('status', 'approved')->get();

        foreach ($franchises as $franchise) {
            $pincodes = $franchise->serviceable_pincodes ?? [];
            if (is_array($pincodes) && in_array($pincode, $pincodes)) {
                return $franchise->id;
            }
            if (is_string($pincodes) && str_contains($pincodes, $pincode)) {
                return $franchise->id;
            }
        }

        return null;
    }

    /**
     * Calculate route and assign courier/franchise
     */
    public function assignRouting(Shipment $shipment, string $deliveryPincode)
    {
        // 1. Check Franchise Interceptor
        $franchiseId = $this->getFranchiseForPincode($deliveryPincode);

        if ($franchiseId) {
            $shipment->franchise_id = $franchiseId;
            $shipment->courier_partner = 'Onestall Franchise'; // Or name of franchise
            return;
        }

        // 2. Normal Aggregator Routing
        $activeCouriers = Courier::where('is_active', true)->pluck('name')->toArray();
        $carriers = !empty($activeCouriers) ? $activeCouriers : ['Onestall Cargo'];
        $shipment->courier_partner = $carriers[array_rand($carriers)]; // Simplistic randomizer for now
    }

    /**
     * Create a shipment cleanly
     */
    public function createShipment(array $data, int $userId): Shipment
    {
        $shipment = new Shipment();
        $shipment->fill($data);
        $shipment->user_id = $userId;
        
        if (isset($data['vendor_id'])) {
            $shipment->vendor_id = $data['vendor_id'];
        }

        $shipment->awb_number = $this->awbService->generateUniqueAwb();
        $shipment->status = 'Manifested';

        // Apply Routing Interceptor
        $this->assignRouting($shipment, $data['delivery_pincode']);

        // Apply rates if not provided
        if (!isset($data['shipping_charge']) || $data['shipping_charge'] == 0) {
            $pickupPin = $data['pickup_pincode'] ?? '000000';
            $deliveryPin = $data['delivery_pincode'];
            $weight = $data['weight_kg'];
            $rates = $this->pricingService->getAvailableRates($pickupPin, $deliveryPin, $weight);
            if (!empty($rates)) {
                $shipment->shipping_charge = $rates[0]['final_rate'];
            }
        }

        $shipment->save();
        
        return $shipment;
    }
}

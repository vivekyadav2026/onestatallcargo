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
    protected $serviceabilityService;

    public function __construct(AwbGeneratorService $awbService, PricingService $pricingService, ServiceabilityService $serviceabilityService)
    {
        $this->awbService = $awbService;
        $this->pricingService = $pricingService;
        $this->serviceabilityService = $serviceabilityService;
    }

    /**
     * Calculate route and assign courier/franchise
     */
    public function assignRouting(Shipment $shipment, string $deliveryPincode)
    {
        if ($shipment->is_overridden) {
            // Respect admin override if it is already set
            return;
        }

        $routing = $this->serviceabilityService->determineRouting($deliveryPincode);

        if ($routing['serviceable']) {
            $shipment->fulfillment_type = $routing['fulfillment_type'];
            
            if ($routing['fulfillment_type'] === 'onestall') {
                $shipment->franchise_id = $routing['franchise_id'];
                $shipment->provider_id = null;
                $shipment->courier_partner = 'Onestall Franchise'; // Legacy compatibility
            } else {
                $shipment->franchise_id = null;
                $shipment->provider_id = $routing['provider_id'];
                
                $provider = Courier::find($routing['provider_id']);
                $shipment->courier_partner = $provider ? $provider->name : 'External Provider';
            }
        } else {
            // Throw exception or set default state if completely unserviceable?
            // Usually you'd throw an exception. We'll set a flag or just leave it.
            $shipment->fulfillment_type = 'unserviceable';
        }
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
            $l = $data['length_cm'] ?? 10;
            $b = $data['width_cm'] ?? 10;
            $h = $data['height_cm'] ?? 10;
            $is_cod = ($data['shipment_type'] ?? 'Prepaid') === 'COD';
            $inv_val = $data['invoice_value'] ?? 0;
            
            $rateData = $this->pricingService->calculateRate(
                $shipment->fulfillment_type ?? 'onestall',
                $pickupPin,
                $deliveryPin,
                $weight,
                $l, $b, $h,
                $is_cod,
                $inv_val,
                false, // is_rto
                false, // is_dto
                0,     // qc_params
                $shipment->provider_id
            );
            $shipment->shipping_charge = $rateData['total'];
            $shipment->total_amount = $rateData['total'];
        }

        if (!isset($shipment->total_amount)) {
            $shipment->total_amount = $shipment->shipping_charge;
        }

        $shipment->save();
        
        if ($shipment->fulfillment_type === 'external') {
            \App\Jobs\ProcessExternalShipment::dispatch($shipment);
        }

        return $shipment;
    }
}

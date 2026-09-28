<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\User;
use App\Services\ShipmentService;
use App\Services\PricingService;
use App\Services\ServiceabilityService;

class ExternalCourierController extends Controller
{
    protected $shipmentService;
    protected $pricingService;
    protected $serviceabilityService;

    public function __construct(ShipmentService $shipmentService, PricingService $pricingService, ServiceabilityService $serviceabilityService)
    {
        $this->shipmentService = $shipmentService;
        $this->pricingService = $pricingService;
        $this->serviceabilityService = $serviceabilityService;
    }

    private function authenticate(Request $request)
    {
        $token = str_replace('Bearer ', '', $request->header('Authorization'));
        if (!$token) {
            abort(401, 'Unauthorized API Key.');
        }
        
        $user = User::where('api_token', $token)->first();
        if (!$user) {
            abort(401, 'Invalid API Key.');
        }

        return $user;
    }

    public function checkServiceability(Request $request)
    {
        $user = $this->authenticate($request);
        $request->validate(['pincode' => 'required|string']);
        
        $franchiseId = $this->serviceabilityService->getFranchiseForPincode($request->pincode);
            
        return response()->json([
            'status' => 'success',
            'is_serviceable' => true, // Assuming generic couriers cover it if no franchise
            'franchise_area' => $franchiseId ? true : false,
            'city' => 'Serviceable City',
            'state' => 'Serviceable State'
        ]);
    }

    public function calculateRate(Request $request)
    {
        $user = $this->authenticate($request);
        $request->validate([
            'pickup_pincode' => 'required|string',
            'delivery_pincode' => 'required|string',
            'weight_kg' => 'required|numeric'
        ]);

        $routing = $this->serviceabilityService->determineRouting($request->delivery_pincode);
        if (!$routing['serviceable']) {
            return response()->json(['status' => 'error', 'message' => 'No service available.'], 404);
        }

        try {
            $rateData = $this->pricingService->calculateRate(
                $routing['fulfillment_type'],
                $request->pickup_pincode,
                $request->delivery_pincode,
                $request->weight_kg,
                10, 10, 10, false, 0, false, false, 0,
                $routing['provider_id']
            );
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'courier_name' => $routing['fulfillment_type'] === 'onestall' ? 'OneStall Cargo' : \App\Models\Courier::find($routing['provider_id'])->name,
                'base_rate' => $rateData['total'],
                'estimated_delivery_days' => 3
            ]
        ]);
    }

    public function createShipment(Request $request)
    {
        $user = $this->authenticate($request);
        
        $validated = $request->validate([
            'order_id' => 'required|string',
            'pickup_pincode' => 'required|string',
            'delivery_pincode' => 'required|string',
            'receiver_name' => 'required|string',
            'receiver_phone' => 'required|string',
            'delivery_address' => 'required|string',
            'weight_kg' => 'required|numeric',
            'is_cod' => 'required|boolean',
            'invoice_value' => 'required|numeric',
            'vendor_id' => 'nullable|integer'
        ]);

        $routing = $this->serviceabilityService->determineRouting($validated['delivery_pincode']);
        if (!$routing['serviceable']) {
            return response()->json(['status' => 'error', 'message' => 'No service available.'], 404);
        }

        try {
            $rateData = $this->pricingService->calculateRate(
                $routing['fulfillment_type'],
                $validated['pickup_pincode'],
                $validated['delivery_pincode'],
                $validated['weight_kg'],
                10, 10, 10,
                $validated['is_cod'],
                $validated['invoice_value'],
                false, false, 0,
                $routing['provider_id']
            );
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 404);
        }

        $shippingCharge = $rateData['total'];

        if (!$validated['is_cod']) {
            try {
                app(\App\Services\WalletService::class)->deduct(
                    $user->id,
                    $shippingCharge,
                    'External API Booking Deduction'
                );
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 402);
            }
        }

        $validated['shipping_charge'] = $shippingCharge;
        $validated['total_amount'] = $shippingCharge;
        $validated['shipment_type'] = 'B2C';

        try {
            $shipment = $this->shipmentService->createShipment($validated, $user->id);

            return response()->json([
                'status' => 'success',
                'message' => 'Shipment created successfully',
                'data' => [
                    'order_id' => $shipment->order_id,
                    'awb_number' => $shipment->awb_number,
                    'label_url' => url("/api/v1/external/label/{$shipment->awb_number}"),
                    'routing_code' => $shipment->courier_partner
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to create shipment.'], 500);
        }
    }

    public function trackShipment(Request $request, $awb)
    {
        $user = $this->authenticate($request);
        
        // Multi-vendor logic: only allow fetching if belongs to this API user's account
        $shipment = Shipment::where('awb_number', $awb)->where('user_id', $user->id)->first();
        
        if (!$shipment) {
            return response()->json(['status' => 'error', 'message' => 'Shipment not found or unauthorized.'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'awb_number' => $shipment->awb_number,
                'current_status' => $shipment->status,
                'scans' => [
                    ['status' => $shipment->status, 'location' => $shipment->delivery_city, 'timestamp' => $shipment->updated_at->toIso8601String()]
                ]
            ]
        ]);
    }
}

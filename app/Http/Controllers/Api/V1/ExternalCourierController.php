<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\User;
use App\Services\ShipmentService;
use App\Services\PricingService;

class ExternalCourierController extends Controller
{
    protected $shipmentService;
    protected $pricingService;

    public function __construct(ShipmentService $shipmentService, PricingService $pricingService)
    {
        $this->shipmentService = $shipmentService;
        $this->pricingService = $pricingService;
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
        
        $franchiseId = $this->shipmentService->getFranchiseForPincode($request->pincode);
            
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

        $rates = $this->pricingService->getAvailableRates(
            $request->pickup_pincode, 
            $request->delivery_pincode, 
            $request->weight_kg
        );

        if (empty($rates)) {
            return response()->json(['status' => 'error', 'message' => 'No service available.'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'courier_name' => $rates[0]['courier_name'],
                'base_rate' => $rates[0]['final_rate'],
                'estimated_delivery_days' => $rates[0]['estimated_delivery_days']
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

        $rates = $this->pricingService->getAvailableRates(
            $validated['pickup_pincode'], 
            $validated['delivery_pincode'], 
            $validated['weight_kg']
        );

        if (empty($rates)) {
            return response()->json(['status' => 'error', 'message' => 'No service available.'], 404);
        }

        $shippingCharge = $rates[0]['final_rate'];

        if (!$validated['is_cod'] && $user->wallet_balance < $shippingCharge) {
            return response()->json(['status' => 'error', 'message' => 'Insufficient wallet balance.'], 400);
        }

        $validated['shipping_charge'] = $shippingCharge;
        $validated['total_amount'] = $shippingCharge;
        $validated['shipment_type'] = 'B2C';

        try {
            $shipment = $this->shipmentService->createShipment($validated, $user->id);

            if (!$validated['is_cod']) {
                $user->wallet_balance -= $shippingCharge;
                $user->save();
            }

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

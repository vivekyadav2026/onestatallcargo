<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\Franchise;
use Illuminate\Support\Str;

class ExternalCourierController extends Controller
{
    // Mock Authentication for external aggregators
    private function authenticate(Request $request)
    {
        $token = $request->header('Authorization');
        if (!$token || !str_starts_with($token, 'Bearer OSC_')) {
            abort(401, 'Unauthorized API Key. Format: Bearer OSC_xxx');
        }
    }

    public function checkServiceability(Request $request)
    {
        $this->authenticate($request);
        
        $request->validate(['pincode' => 'required|string']);
        
        $isFranchise = Franchise::where('status', 'approved')
            ->whereJsonContains('serviceable_pincodes', $request->pincode)
            ->exists();
            
        return response()->json([
            'status' => 'success',
            'is_serviceable' => true,
            'franchise_area' => $isFranchise,
            'city' => 'Serviceable City',
            'state' => 'Serviceable State'
        ]);
    }

    public function calculateRate(Request $request)
    {
        $this->authenticate($request);
        
        $request->validate([
            'pickup_pincode' => 'required|string',
            'delivery_pincode' => 'required|string',
            'weight_kg' => 'required|numeric'
        ]);

        $baseRate = 45;
        $additionalRate = ceil(max(0, $request->weight_kg - 0.5) / 0.5) * 40;
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'courier_name' => 'Onestall Cargo',
                'base_rate' => $baseRate + $additionalRate,
                'estimated_delivery_days' => 3
            ]
        ]);
    }

    public function createShipment(Request $request)
    {
        $this->authenticate($request);
        
        $validated = $request->validate([
            'order_id' => 'required|string',
            'pickup_pincode' => 'required|string',
            'delivery_pincode' => 'required|string',
            'receiver_name' => 'required|string',
            'receiver_phone' => 'required|string',
            'delivery_address' => 'required|string',
            'weight_kg' => 'required|numeric',
            'is_cod' => 'required|boolean',
            'invoice_value' => 'required|numeric'
        ]);

        $awb = 'OSC' . strtoupper(Str::random(8));
        
        return response()->json([
            'status' => 'success',
            'message' => 'Shipment created successfully via Onestall Cargo API',
            'data' => [
                'order_id' => $validated['order_id'],
                'awb_number' => $awb,
                'label_url' => url("/api/v1/external/label/{$awb}"),
                'routing_code' => 'OSC-EXTERNAL'
            ]
        ]);
    }

    public function trackShipment(Request $request, $awb)
    {
        $this->authenticate($request);
        
        $shipment = Shipment::where('awb_number', $awb)->first();
        
        if (!$shipment) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'awb_number' => $awb,
                    'current_status' => 'Manifested',
                    'scans' => [
                        ['status' => 'Manifested', 'location' => 'Origin Hub', 'timestamp' => now()->toIso8601String()]
                    ]
                ]
            ]);
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

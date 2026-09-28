<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ShipmentApiController extends Controller
{
    public function calculateRate(Request $request)
    {
        $validated = $request->validate([
            'pickup_pincode' => 'required|string',
            'delivery_pincode' => 'required|string',
            'weight_kg' => 'required|numeric',
            'is_cod' => 'required|boolean',
            'invoice_value' => 'required|numeric'
        ]);

        $routing = app(\App\Services\ServiceabilityService::class)->determineRouting($validated['delivery_pincode']);
        if (!$routing['serviceable']) {
            return response()->json(['status' => 'error', 'message' => 'No service available.'], 404);
        }

        try {
            $rateData = app(\App\Services\PricingService::class)->calculateRate(
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

        return response()->json([
            'status' => 'success',
            'data' => $rateData
        ]);
    }

    public function book(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'nullable|string',
            'pickup_pincode' => 'required|string',
            'pickup_address' => 'required|string',
            'pickup_city' => 'required|string',
            'receiver_name' => 'required|string',
            'receiver_phone' => 'required|string',
            'delivery_address' => 'required|string',
            'delivery_city' => 'required|string',
            'delivery_pincode' => 'required|string',
            'weight_kg' => 'required|numeric',
            'is_cod' => 'required|boolean',
            'invoice_value' => 'required|numeric',
            'product_name' => 'nullable|string'
        ]);

        $user = Auth::user();

        $routing = app(\App\Services\ServiceabilityService::class)->determineRouting($validated['delivery_pincode']);
        if (!$routing['serviceable']) {
            return response()->json(['status' => 'error', 'message' => 'Delivery pincode unserviceable'], 400);
        }

        try {
            $rateData = app(\App\Services\PricingService::class)->calculateRate(
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
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }

        $totalAmount = $rateData['total'];

        if (!$validated['is_cod']) {
            try {
                app(\App\Services\WalletService::class)->deduct(
                    $user->id,
                    $totalAmount,
                    'Shipment Booking Deduction'
                );
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 402);
            }
        }

        $validated['shipping_charge'] = $totalAmount;
        $validated['total_amount'] = $totalAmount;

        try {
            $shipment = app(\App\Services\ShipmentService::class)->createShipment($validated, $user->id);
            return response()->json([
                'status' => 'success',
                'message' => 'Shipment created successfully.',
                'data' => [
                    'awb_number' => $shipment->awb_number,
                    'status' => $shipment->status,
                    'total_amount' => $shipment->total_amount
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function track($awb)
    {
        $shipment = Shipment::where('awb_number', $awb)->first();

        if (!$shipment) {
            return response()->json(['status' => 'error', 'message' => 'Shipment not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'awb_number' => $shipment->awb_number,
                'status' => $shipment->status,
                'receiver_city' => $shipment->delivery_city,
                'updated_at' => $shipment->updated_at->toIso8601String()
            ]
        ]);
    }
}

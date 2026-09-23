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
            'weight_kg' => 'required|numeric',
            'is_cod' => 'required|boolean',
            'invoice_value' => 'required|numeric'
        ]);

        $baseRate = 45;
        $extraWeight = max(0, $validated['weight_kg'] - 0.5);
        $additionalRate = ceil($extraWeight / 0.5) * 40;
        $codSurcharge = $validated['is_cod'] ? max(50, $validated['invoice_value'] * 0.02) : 0;
        
        $totalAmount = $baseRate + $additionalRate + $codSurcharge;

        return response()->json([
            'status' => 'success',
            'data' => [
                'weight_kg' => $validated['weight_kg'],
                'base_rate' => $baseRate,
                'additional_rate' => $additionalRate,
                'cod_surcharge' => $codSurcharge,
                'total_amount' => $totalAmount
            ]
        ]);
    }

    public function book(Request $request)
    {
        $validated = $request->validate([
            'receiver_name' => 'required|string',
            'receiver_phone' => 'required|string',
            'delivery_address' => 'required|string',
            'delivery_city' => 'required|string',
            'delivery_pincode' => 'required|string',
            'weight_kg' => 'required|numeric',
            'is_cod' => 'required|boolean',
            'invoice_value' => 'required|numeric'
        ]);

        $user = Auth::user(); // Authenticated via Token Guard

        $totalAmount = 45 + (ceil(max(0, $validated['weight_kg'] - 0.5) / 0.5) * 40);
        if ($validated['is_cod']) {
            $totalAmount += max(50, $validated['invoice_value'] * 0.02);
        }

        if ($user->wallet_balance < $totalAmount && !$validated['is_cod']) {
            return response()->json(['status' => 'error', 'message' => 'Insufficient wallet balance.'], 402);
        }

        $user->wallet_balance -= $totalAmount;
        $user->save();

        $shipment = new Shipment();
        $shipment->user_id = $user->id;
        $shipment->awb_number = 'OSC' . strtoupper(Str::random(8));
        $shipment->receiver_name = $validated['receiver_name'];
        $shipment->receiver_phone = $validated['receiver_phone'];
        $shipment->delivery_address = $validated['delivery_address'];
        $shipment->delivery_city = $validated['delivery_city'];
        $shipment->delivery_pincode = $validated['delivery_pincode'];
        $shipment->weight_kg = $validated['weight_kg'];
        $shipment->is_cod = $validated['is_cod'];
        $shipment->invoice_value = $validated['invoice_value'];
        $shipment->total_amount = $totalAmount;
        $shipment->status = 'Manifested';
        $shipment->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Shipment created successfully.',
            'data' => [
                'awb_number' => $shipment->awb_number,
                'status' => $shipment->status,
                'total_amount' => $shipment->total_amount
            ]
        ], 201);
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

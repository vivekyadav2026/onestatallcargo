<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class AdminShipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Shipment::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('awb_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $shipments = $query->paginate(15)->appends($request->all());

        return view('admin.shipments.index', compact('shipments'));
    }

    public function create()
    {
        $sellers = \App\Models\User::where('user_type', 'seller')->get();
        return view('admin.shipments.create', compact('sellers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
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
            'length_cm' => 'nullable|numeric',
            'width_cm' => 'nullable|numeric',
            'height_cm' => 'nullable|numeric',
            'is_cod' => 'required|boolean',
            'invoice_value' => 'required|numeric',
            'product_name' => 'nullable|string'
        ]);

        $routing = app(\App\Services\ServiceabilityService::class)->determineRouting($validated['delivery_pincode']);
        if (!$routing['serviceable']) {
            return back()->with('error', 'Delivery pincode unserviceable');
        }

        try {
            $rateData = app(\App\Services\PricingService::class)->calculateRate(
                $routing['fulfillment_type'],
                $validated['pickup_pincode'],
                $validated['delivery_pincode'],
                $validated['weight_kg'],
                $validated['length_cm'] ?? 10,
                $validated['width_cm'] ?? 10,
                $validated['height_cm'] ?? 10,
                $validated['is_cod'],
                $validated['invoice_value'],
                false, false, 0,
                $routing['provider_id']
            );
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        $totalAmount = $rateData['total'];

        if (!$validated['is_cod']) {
            try {
                app(\App\Services\WalletService::class)->deduct(
                    $validated['user_id'],
                    $totalAmount,
                    'Admin Manual Booking Deduction'
                );
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        }

        $validated['shipping_charge'] = $totalAmount;
        $validated['total_amount'] = $totalAmount;
        $validated['shipment_type'] = 'B2C';

        try {
            $shipment = app(\App\Services\ShipmentService::class)->createShipment($validated, $validated['user_id']);
            return redirect()->route('admin.shipments.index')->with('success', 'Shipment created successfully. AWB: ' . $shipment->awb_number);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}

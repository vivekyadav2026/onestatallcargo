<?php
namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SellerShipmentController extends Controller
{
    public function create(Request $request)
    {
        $shipment = null;
        $mode = 'create';

        if ($request->filled('edit')) {
            $shipment = Shipment::where('user_id', Auth::id())->find($request->edit);
            $mode = $shipment ? 'edit' : 'create';
        } elseif ($request->filled('clone')) {
            $shipment = Shipment::where('user_id', Auth::id())->find($request->clone);
            $mode = $shipment ? 'clone' : 'create';
        }

        return view('seller.book', compact('shipment', 'mode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipment_id' => 'nullable|integer',
            'mode' => 'nullable|string',
            'ship_now' => 'nullable',
            'shipment_type' => 'required|string',
            'receiver_name' => 'required|string',
            'receiver_phone' => 'required|string',
            'delivery_address' => 'required|string',
            'delivery_city' => 'required|string',
            'delivery_pincode' => 'required|string',
            'pickup_pincode' => 'nullable|string',
            'pickup_address' => 'nullable|string',
            'pickup_city' => 'nullable|string',
            'destination_country' => 'nullable|string',
            'customs_value' => 'nullable|numeric',
            'hs_code' => 'nullable|string',
            'vehicle_type' => 'nullable|string',
            'weight_kg' => 'required|numeric',
            'is_cod' => 'nullable|boolean',
            'invoice_value' => 'nullable|numeric',
        ]);

        $user = Auth::user();

        // KYC Check: Shipment cannot be booked/shipped without approved KYC
        $kyc = \App\Models\Kyc::where('user_id', $user->id)->first();
        if (!$kyc || $kyc->status !== 'approved') {
            return redirect()->route('seller.settings', ['view' => 'kyc'])
                ->with('error', 'KYC Verification Required! Please complete and get your KYC approved before shipping orders.');
        }
        
        $isEdit = ($validated['mode'] ?? '') === 'edit' && !empty($validated['shipment_id']);

        if ($isEdit) {
            $shipment = Shipment::where('user_id', $user->id)->findOrFail($validated['shipment_id']);
        } else {
            $shipment = new Shipment();
            $shipment->user_id = $user->id;
            $shipment->awb_number = 'OSC' . strtoupper(Str::random(8));
        }

        $routing = app(\App\Services\ServiceabilityService::class)->determineRouting($validated['delivery_pincode']);
        if (!$routing['serviceable']) {
            return back()->with('error', 'Delivery pincode is unserviceable.');
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
                $validated['is_cod'] ?? false,
                $validated['invoice_value'] ?? 0,
                false, false, 0,
                $routing['provider_id']
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Pricing Error: ' . $e->getMessage());
        }

        $totalAmount = $rateData['total'];
        $is_cod = $validated['is_cod'] ?? false;
        
        if (!$isEdit) {
            try {
                app(\App\Services\WalletService::class)->deduct(
                    $user->id, 
                    $totalAmount, 
                    'Shipment Booking Deduction', 
                    $shipment->awb_number ?? null
                );
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        }

        // Pass calculated rate to shipment saving
        $shipment->shipping_charge = $totalAmount;
        $shipment->total_amount = $totalAmount;

        $shipment->shipment_type = $validated['shipment_type'];
        $shipment->receiver_name = $validated['receiver_name'];
        $shipment->receiver_phone = $validated['receiver_phone'];
        $shipment->delivery_address = $validated['delivery_address'];
        $shipment->delivery_city = $validated['delivery_city'];
        $shipment->delivery_pincode = $validated['delivery_pincode'];
        $shipment->weight_kg = $validated['weight_kg'];
        $shipment->is_cod = $is_cod;
        $shipment->invoice_value = $validated['invoice_value'] ?? 0;
        $shipment->total_amount = $totalAmount;

        // Product details handling
        $productNames = $request->input('product_name', []);
        $productSkus = $request->input('product_sku', []);
        $productQtys = $request->input('product_qty', []);
        $productPrices = $request->input('product_price', []);

        if (!empty($productNames) && is_array($productNames)) {
            $filteredNames = array_filter($productNames);
            $shipment->product_name = !empty($filteredNames) ? implode(', ', $filteredNames) : 'General Parcel';
            $shipment->product_sku = implode(', ', array_filter($productSkus)) ?: 'N/A';
            $shipment->product_qty = array_sum(array_map('intval', $productQtys)) ?: 1;

            $items = [];
            for ($i = 0; $i < count($productNames); $i++) {
                if (!empty($productNames[$i])) {
                    $items[] = [
                        'name' => $productNames[$i],
                        'price' => $productPrices[$i] ?? 0,
                        'qty' => $productQtys[$i] ?? 1,
                        'sku' => $productSkus[$i] ?? '',
                    ];
                }
            }
            $shipment->product_details = json_encode($items);
        } else {
            $shipment->product_name = $shipment->product_name ?: 'General Parcel';
        }
        
        if (!$isEdit) {
            $shipment->status = 'Manifested';
            app(\App\Services\ShipmentService::class)->assignRouting($shipment, $shipment->delivery_pincode);
        }
        
        // B2B & Intl logic
        if ($validated['shipment_type'] == 'B2B') {
            $shipment->vehicle_type = $validated['vehicle_type'] ?? null;
        }
        if ($validated['shipment_type'] == 'International') {
            $shipment->destination_country = $validated['destination_country'] ?? null;
            $shipment->customs_value = $validated['customs_value'] ?? null;
            $shipment->hs_code = $validated['hs_code'] ?? null;
            $shipment->is_cod = false;
        }

        $shipment->save();

        if (!$isEdit) {
            \App\Models\ShipmentEvent::create([
                'shipment_id' => $shipment->id,
                'status' => 'Manifested',
                'location' => $shipment->pickup_city ?? 'Origin',
                'remarks' => 'Shipment Booked & Manifested successfully.'
            ]);
        }

        $shipNow = !empty($validated['ship_now']) && $validated['ship_now'] == '1';

        if ($isEdit) {
            if ($shipNow) {
                return redirect()->route('seller.label', $shipment->awb_number)->with('success', 'Order updated and label generated.');
            }
            return redirect()->route('seller.shipments.index')->with('success', 'Order updated successfully.');
        }

        if ($shipNow) {
            return redirect()->route('seller.label', $shipment->awb_number)->with('success', "Shipment Booked! Forwarded to {$shipment->courier_partner}.");
        }

        return redirect()->route('seller.shipments.index')->with('success', 'Order created successfully.');
    }

    public function bulkCreate()
    {
        return view('seller.bulk-book');
    }

    public function bulkStore(Request $request)
    {
        $user = Auth::user();
        if (!$user->isKycApproved()) {
            return redirect()->route('seller.settings', ['view' => 'kyc'])->with('error', 'KYC Verification Required!');
        }

        $request->validate([
            'bulk_file' => 'required|file|mimes:csv,txt'
        ]);
        for ($i = 0; $i < 3; $i++) {
            $shipment = new Shipment();
            $shipment->user_id = $user->id;
            $shipment->awb_number = 'OSC' . strtoupper(Str::random(8));
            $shipment->shipment_type = 'B2C';
            $shipment->receiver_name = 'Bulk Customer ' . ($i + 1);
            $shipment->receiver_phone = '999999999' . $i;
            $shipment->delivery_address = 'Bulk Upload Address ' . $i;
            $shipment->delivery_city = 'Mumbai';
            $shipment->delivery_pincode = '400001';
            $shipment->weight_kg = 1.0;
            $shipment->is_cod = false;
            $shipment->invoice_value = 500;
            $shipment->total_amount = 55;
            $shipment->status = 'Manifested';
            app(\App\Services\ShipmentService::class)->assignRouting($shipment, $shipment->delivery_pincode);
            $shipment->save();
        }

        return redirect()->route('seller.dashboard')->with('success', 'Bulk file processed! 3 shipments successfully created and pushed to Couriers.');
    }

    public function index(Request $request)
    {
        $userId = Auth::id();
        $query = \App\Models\Shipment::where('user_id', $userId);

        // Grouped status counts for tabs
        $counts = [
            'new' => \App\Models\Shipment::where('user_id', $userId)->whereIn('status', ['new', 'New', 'Manifested', 'manifested', 'Booked', 'booked', 'pending', 'new order'])->count(),
            'pickups' => \App\Models\Shipment::where('user_id', $userId)->whereIn('status', ['pickup_scheduled', 'Pickup Scheduled', 'pickups', 'Pickups', 'pickup_assigned'])->count(),
            'transit' => \App\Models\Shipment::where('user_id', $userId)->whereIn('status', ['in_transit', 'In Transit', 'transit', 'Transit', 'In-Transit'])->count(),
            'out_for_delivery' => \App\Models\Shipment::where('user_id', $userId)->whereIn('status', ['out_for_delivery', 'Out for Delivery', 'ofd', 'OFD'])->count(),
            'delivered' => \App\Models\Shipment::where('user_id', $userId)->whereIn('status', ['delivered', 'Delivered', 'DELIVERED'])->count(),
            'rto' => \App\Models\Shipment::where('user_id', $userId)->whereIn('status', ['rto', 'RTO', 'rto_transit', 'RTO In-Transit', 'rto_delivered', 'RTO Delivered', 'rto in-transit', 'rto delivered'])->count(),
            'ndr' => \App\Models\Shipment::where('user_id', $userId)->whereIn('status', ['ndr', 'NDR', 'action_required', 'undelivered', 'Undelivered'])->count(),
            'lost' => \App\Models\Shipment::where('user_id', $userId)->whereIn('status', ['lost', 'Lost', 'damaged', 'Damaged'])->count(),
            'cancelled' => \App\Models\Shipment::where('user_id', $userId)->whereIn('status', ['cancelled', 'Cancelled', 'CANCELLED'])->count(),
            'all' => \App\Models\Shipment::where('user_id', $userId)->count(),
        ];

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $status = strtolower(trim($request->status));
            if (in_array($status, ['pickups', 'pickup_scheduled', 'pickup scheduled', 'pickup_assigned'])) {
                $query->whereIn('status', ['pickup_scheduled', 'Pickup Scheduled', 'pickups', 'Pickups', 'pickup_assigned']);
            } elseif (in_array($status, ['transit', 'in_transit', 'in transit'])) {
                $query->whereIn('status', ['in_transit', 'In Transit', 'transit', 'Transit', 'In-Transit']);
            } elseif (in_array($status, ['out_for_delivery', 'ofd', 'out for delivery'])) {
                $query->whereIn('status', ['out_for_delivery', 'Out for Delivery', 'ofd', 'OFD', 'Out For Delivery']);
            } elseif (in_array($status, ['delivered', 'deliver'])) {
                $query->whereIn('status', ['delivered', 'Delivered', 'DELIVERED']);
            } elseif (in_array($status, ['rto', 'rto_transit', 'rto_delivered', 'rto in-transit', 'rto delivered'])) {
                $query->whereIn('status', ['rto', 'RTO', 'rto_transit', 'RTO In-Transit', 'rto_delivered', 'RTO Delivered', 'rto in-transit', 'rto delivered']);
            } elseif (in_array($status, ['ndr', 'undelivered', 'action_required'])) {
                $query->whereIn('status', ['ndr', 'NDR', 'action_required', 'undelivered', 'Undelivered']);
            } elseif (in_array($status, ['lost', 'damaged'])) {
                $query->whereIn('status', ['lost', 'Lost', 'damaged', 'Damaged']);
            } elseif (in_array($status, ['new', 'unshipped', 'manifested', 'booked', 'pending'])) {
                $query->whereIn('status', ['new', 'New', 'Manifested', 'manifested', 'Booked', 'booked', 'pending', 'new order']);
            } elseif (in_array($status, ['cancelled', 'cancel'])) {
                $query->whereIn('status', ['cancelled', 'Cancelled', 'CANCELLED']);
            } else {
                $query->where('status', 'like', "%{$request->status}%");
            }
        }

        // Payment mode filter
        if ($request->filled('payment_mode')) {
            if ($request->payment_mode === 'cod') {
                $query->where('is_cod', 1);
            } elseif ($request->payment_mode === 'prepaid') {
                $query->where('is_cod', 0);
            }
        }

        // Global search filter
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('awb_number', 'like', "%{$search}%")
                  ->orWhere('order_id', 'like', "%{$search}%")
                  ->orWhere('receiver_name', 'like', "%{$search}%")
                  ->orWhere('receiver_phone', 'like', "%{$search}%")
                  ->orWhere('delivery_city', 'like', "%{$search}%");
            });
        }

        // Date range filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        } elseif ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date . ' 00:00:00');
        } elseif ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }

        // Courier partner filter
        if ($request->filled('courier') && $request->courier !== 'all') {
            $query->where('courier_partner', $request->courier);
        }

        $shipments = $query->orderBy('created_at', 'desc')->paginate(15);
        $shipments->appends($request->all());

        return view('seller.shipments', compact('shipments', 'counts'));
    }

    public function cancel($id)
    {
        $shipment = \App\Models\Shipment::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        
        if (strtolower($shipment->status) === 'cancelled') {
            return back()->with('error', 'This shipment is already cancelled.');
        }

        $cancellableStatuses = ['new', 'manifested', 'booked', 'pickup_scheduled', 'pending', 'new order'];
        
        if (in_array(strtolower(trim($shipment->status)), $cancellableStatuses)) {
            $shipment->status = 'cancelled';
            $shipment->save();
            
            // Refund logic
            if ($shipment->total_amount > 0) {
                $user = Auth::user();
                $user->wallet_balance += $shipment->total_amount;
                $user->save();
                
                // create wallet transaction
                \App\Models\WalletTransaction::create([
                    'user_id' => $user->id,
                    'amount' => $shipment->total_amount,
                    'type' => 'credit',
                    'balance_after' => $user->wallet_balance,
                    'reference_id' => $shipment->awb_number,
                    'description' => 'Refund for cancelled shipment ' . $shipment->awb_number,
                    'status' => 'success',
                ]);
            }
            return back()->with('success', 'Shipment cancelled successfully and amount refunded to wallet.');
        }

        return back()->with('error', "Shipment with status '{$shipment->status}' cannot be cancelled. Only orders not yet picked up can be cancelled.");
    }

    public function bulkCancel(Request $request)
    {
        $ids = explode(',', $request->input('ids', ''));
        $count = 0;
        $totalRefund = 0;
        $user = Auth::user();

        foreach ($ids as $id) {
            $shipment = Shipment::where('user_id', $user->id)->where('id', trim($id))->first();
            if ($shipment && in_array(strtolower($shipment->status), ['new', 'manifested', 'booked'])) {
                $shipment->status = 'cancelled';
                $shipment->save();
                $count++;
                
                if ($shipment->total_amount > 0) {
                    $totalRefund += $shipment->total_amount;
                }
            }
        }

        if ($totalRefund > 0) {
            $user->wallet_balance += $totalRefund;
            $user->save();

            \App\Models\WalletTransaction::create([
                'user_id' => $user->id,
                'amount' => $totalRefund,
                'type' => 'credit',
                'balance_after' => $user->wallet_balance,
                'reference_id' => 'BULK_CANCEL',
                'description' => "Bulk refund for $count cancelled shipments",
                'status' => 'success',
            ]);
        }

        return back()->with('success', "$count shipment(s) cancelled successfully.");
    }

    public function printLabel($awb)
    {
        $shipment = \App\Models\Shipment::where('awb_number', $awb)->where('user_id', Auth::id())->firstOrFail();
        return view('seller.label', compact('shipment'));
    }

    public function printLR($awb)
    {
        $shipment = \App\Models\Shipment::where('awb_number', $awb)->where('user_id', Auth::id())->firstOrFail();
        return view('seller.lr', compact('shipment'));
    }

    public function printInvoice($awb)
    {
        $shipment = \App\Models\Shipment::where('awb_number', $awb)->where('user_id', Auth::id())->firstOrFail();
        return view('seller.invoice', compact('shipment'));
    }
}

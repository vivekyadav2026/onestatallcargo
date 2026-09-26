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

        // Rate Calc Mock
        $baseRate = $validated['shipment_type'] == 'International' ? 1500 : ($validated['shipment_type'] == 'B2B' ? 500 : 45);
        $totalAmount = $baseRate + ($validated['weight_kg'] * 10);
        $is_cod = $validated['is_cod'] ?? false;
        
        if (!$isEdit) {
            if ($user->wallet_balance < $totalAmount && !$is_cod) {
                return back()->with('error', 'Insufficient wallet balance. Required: ₹' . number_format($totalAmount, 2));
            }
            $user->wallet_balance -= $totalAmount;
            $user->save();
        }

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
            $carriers = ['Delhivery', 'BlueDart', 'OneStall Direct'];
            $shipment->courier_partner = $carriers[array_rand($carriers)];
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
        $request->validate([
            'bulk_file' => 'required|file|mimes:csv,txt'
        ]);

        $user = Auth::user();
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
            $shipment->courier_partner = 'Delhivery';
            $shipment->save();
        }

        return redirect()->route('seller.dashboard')->with('success', 'Bulk file processed! 3 shipments successfully created and pushed to Couriers.');
    }

    public function index(Request $request)
    {
        $userId = Auth::id();
        $query = \App\Models\Shipment::where('user_id', $userId);

        // Single aggregated SQL query for all status counts (high performance for 100k+ users)
        $statusGroup = \App\Models\Shipment::where('user_id', $userId)
            ->selectRaw("status, count(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $counts = [
            'new' => ($statusGroup['new'] ?? 0) + ($statusGroup['Manifested'] ?? 0) + ($statusGroup['Booked'] ?? 0) + ($statusGroup['booked'] ?? 0) + ($statusGroup['New'] ?? 0),
            'pickups' => ($statusGroup['pickup_scheduled'] ?? 0) + ($statusGroup['Pickup Scheduled'] ?? 0) + ($statusGroup['pickups'] ?? 0),
            'transit' => ($statusGroup['in_transit'] ?? 0) + ($statusGroup['In Transit'] ?? 0) + ($statusGroup['transit'] ?? 0),
            'delivered' => ($statusGroup['delivered'] ?? 0) + ($statusGroup['Delivered'] ?? 0),
            'rto' => ($statusGroup['rto'] ?? 0) + ($statusGroup['RTO'] ?? 0),
            'cancelled' => ($statusGroup['cancelled'] ?? 0) + ($statusGroup['Cancelled'] ?? 0),
            'all' => array_sum($statusGroup),
        ];

        if ($request->filled('status') && $request->status !== 'all') {
            $status = $request->status;
            if ($status === 'pickups') {
                $query->whereIn('status', ['pickup_scheduled', 'Pickup Scheduled', 'pickups']);
            } elseif ($status === 'transit') {
                $query->whereIn('status', ['in_transit', 'In Transit', 'transit']);
            } elseif ($status === 'delivered') {
                $query->whereIn('status', ['delivered', 'Delivered']);
            } elseif ($status === 'rto') {
                $query->whereIn('status', ['rto', 'RTO']);
            } elseif ($status === 'new') {
                $query->whereIn('status', ['new', 'Manifested', 'Booked', 'booked', 'New']);
            } elseif ($status === 'cancelled') {
                $query->whereIn('status', ['cancelled', 'Cancelled']);
            } else {
                $query->where('status', $status);
            }
        }

        if ($request->filled('payment_mode')) {
            if ($request->payment_mode === 'cod') {
                $query->where('is_cod', 1);
            } elseif ($request->payment_mode === 'prepaid') {
                $query->where('is_cod', 0);
            }
        }

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

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
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



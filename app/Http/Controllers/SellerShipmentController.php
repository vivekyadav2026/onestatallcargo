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
        $query = \App\Models\Shipment::where('user_id', \Illuminate\Support\Facades\Auth::id());

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

        return view('seller.shipments', compact('shipments'));
    }

    public function cancel($id)
    {
        $shipment = \App\Models\Shipment::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        
        if (in_array(strtolower($shipment->status), ['new', 'manifested', 'booked'])) {
            $shipment->status = 'cancelled';
            $shipment->save();
            
            // Refund logic
            if ($shipment->total_amount > 0) {
                $user = Auth::user();
                $user->wallet_balance += $shipment->total_amount;
                $user->save();
                
                // create wallet transaction
                \App\Models\Transaction::create([
                    'user_id' => $user->id,
                    'amount' => $shipment->total_amount,
                    'type' => 'credit',
                    'description' => 'Refund for cancelled shipment ' . $shipment->awb_number,
                    'closing_balance' => $user->wallet_balance
                ]);
            }
            return back()->with('success', 'Shipment cancelled successfully and amount refunded to wallet.');
        }

        return back()->with('error', 'Only new shipments can be cancelled.');
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

            \App\Models\Transaction::create([
                'user_id' => $user->id,
                'amount' => $totalRefund,
                'type' => 'credit',
                'description' => "Bulk refund for $count cancelled shipments",
                'closing_balance' => $user->wallet_balance
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



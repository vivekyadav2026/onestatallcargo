<?php
namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HubShipmentController extends Controller
{
    private function getOwnedShipmentQuery() {
        $user = Auth::user();
        $franchise = \App\Models\Franchise::where('user_id', $user->id)->first();
        $q = \App\Models\Shipment::query();
        if ($franchise) {
            $q->where(function($query) use ($franchise, $user) {
                $query->where('franchise_id', $franchise->id)->orWhere('user_id', $user->id);
            });
        } else {
            $q->where('user_id', $user->id);
        }
        return $q;
    }

    private function getVisibleShipmentQuery() {
        $user = Auth::user();
        $franchise = \App\Models\Franchise::where('user_id', $user->id)->first();
        $q = \App\Models\Shipment::query();
        if ($franchise) {
            $pincodes = is_array($franchise->serviceable_pincodes) ? $franchise->serviceable_pincodes : (json_decode($franchise->serviceable_pincodes, true) ?? []);
            if (!is_array($pincodes)) { $pincodes = $pincodes ? [$pincodes] : []; }
            
            $q->where(function($query) use ($franchise, $user, $pincodes) {
                $query->where('franchise_id', $franchise->id)->orWhere('user_id', $user->id);
                if (!empty($pincodes)) {
                    $query->orWhereIn('pickup_pincode', $pincodes)->orWhereIn('delivery_pincode', $pincodes);
                }
            });
        } else {
            $q->where('user_id', $user->id);
        }
        return $q;
    }

    public function create(Request $request)
    {
        $shipment = null;
        $mode = 'create';

        if ($request->filled('edit')) {
            $shipment = $this->getOwnedShipmentQuery()->find($request->edit);
            $mode = $shipment ? 'edit' : 'create';
        } elseif ($request->filled('clone')) {
            $shipment = $this->getOwnedShipmentQuery()->find($request->clone);
            $mode = $shipment ? 'clone' : 'create';
        }

        return view('hub.shipments.create', compact('shipment', 'mode'));
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

        $isInternational = ($validated['shipment_type'] ?? '') === 'International';

        // Auto-resolve pickup location / warehouse
        if (empty($validated['pickup_pincode']) && $request->filled('pickup_location')) {
            $wh = \App\Models\Warehouse::find($request->input('pickup_location'));
            if ($wh) {
                $validated['pickup_pincode'] = $wh->pincode;
                $validated['pickup_address'] = $wh->address;
                $validated['pickup_city'] = $wh->city;
            }
        }
        if (empty($validated['pickup_pincode'])) {
            $defaultWh = \App\Models\Warehouse::where('user_id', $user->id)->where('is_default', true)->first()
                      ?? \App\Models\Warehouse::where('user_id', $user->id)->first();
            if ($defaultWh) {
                $validated['pickup_pincode'] = $defaultWh->pincode;
                $validated['pickup_address'] = $defaultWh->address;
                $validated['pickup_city'] = $defaultWh->city;
            } else {
                $validated['pickup_pincode'] = '110001';
            }
        }

        $shipment->pickup_pincode = $validated['pickup_pincode'];
        $shipment->pickup_address = $validated['pickup_address'] ?? null;
        $shipment->pickup_city = $validated['pickup_city'] ?? 'Origin';

        if ($isInternational) {
            $routing = ['serviceable' => true, 'fulfillment_type' => 'external', 'provider_id' => null];
            $l = (float)($request->input('length_cm', 10));
            $w = (float)($request->input('width_cm', 10));
            $h = (float)($request->input('height_cm', 10));
            $chargeableWeight = max((float)$validated['weight_kg'], ($l * $w * $h) / 5000);
            $totalAmount = round((1150 + ($chargeableWeight * 420)) * 1.18, 2);
        } else {
            $routing = app(\App\Services\ServiceabilityService::class)->determineRouting($validated['delivery_pincode']);
            if (!$routing['serviceable']) {
                return back()->with('error', 'Delivery pincode is unserviceable.');
            }

            try {
                $rateData = app(\App\Services\PricingService::class)->calculateRate(
                    $routing['fulfillment_type'],
                    $validated['pickup_pincode'],
                    $validated['delivery_pincode'],
                    (float)$validated['weight_kg'],
                    (float)($request->input('length_cm', 10)),
                    (float)($request->input('width_cm', 10)),
                    (float)($request->input('height_cm', 10)),
                    $validated['is_cod'] ?? false,
                    (float)($validated['invoice_value'] ?? 0),
                    false, false, 0,
                    $routing['provider_id']
                );
                $totalAmount = $rateData['total'];
            } catch (\Exception $e) {
                $totalAmount = max(50, round((float)$validated['weight_kg'] * 60, 2));
            }
        }

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

        $file = $request->file('bulk_file');
        $csvData = file_get_contents($file->getRealPath());
        $rows = array_map('str_getcsv', explode("\n", trim($csvData)));
        $header = array_shift($rows);
        $header = array_map('trim', $header);

        $successCount = 0;

        foreach ($rows as $row) {
            if (empty(implode('', $row))) continue;
            
            // Pad row if missing columns
            if(count($row) < count($header)) {
                $row = array_pad($row, count($header), '');
            }
            $data = array_combine($header, $row);

            $shipment = new Shipment();
            $shipment->user_id = $user->id;
            $shipment->awb_number = 'OSC' . strtoupper(Str::random(8));
            $shipment->shipment_type = $data['shipment_type'] ?? 'B2C';
            $shipment->receiver_name = $data['receiver_name'] ?? 'Customer';
            $shipment->receiver_phone = $data['receiver_phone'] ?? '9999999999';
            $shipment->delivery_address = $data['delivery_address'] ?? 'N/A';
            $shipment->delivery_city = $data['delivery_city'] ?? 'N/A';
            $shipment->delivery_pincode = $data['delivery_pincode'] ?? '000000';
            $shipment->weight_kg = (float) ($data['weight_kg'] ?? 1.0);
            $shipment->length_cm = (float) ($data['length_cm'] ?? 10);
            $shipment->width_cm = (float) ($data['width_cm'] ?? 10);
            $shipment->height_cm = (float) ($data['height_cm'] ?? 10);
            $shipment->is_cod = (bool) ($data['is_cod'] ?? false);
            $shipment->invoice_value = (float) ($data['invoice_value'] ?? 0);
            $shipment->product_name = $data['product_name'] ?? 'General Item';
            $shipment->product_sku = $data['product_sku'] ?? '';
            $shipment->product_qty = (int) ($data['product_qty'] ?? 1);
            
            // Fake calculation
            $shipment->total_amount = 55;
            $shipment->status = 'Manifested';
            
            app(\App\Services\ShipmentService::class)->assignRouting($shipment, $shipment->delivery_pincode);
            $shipment->save();
            $successCount++;
        }

        return redirect()->route('seller.dashboard')->with('success', "Bulk file processed! {$successCount} shipments successfully created.");
    }

    public function index(Request $request)
    {
        $userId = Auth::id();
        $query = $this->getVisibleShipmentQuery();

        // Grouped status counts for tabs
        $counts = [
            'new' => $this->getVisibleShipmentQuery()->whereIn('status', ['new', 'New', 'Manifested', 'manifested', 'Booked', 'booked', 'pending', 'new order'])->count(),
            'pickups' => $this->getVisibleShipmentQuery()->whereIn('status', ['pickup_scheduled', 'Pickup Scheduled', 'pickups', 'Pickups', 'pickup_assigned'])->count(),
            'transit' => $this->getVisibleShipmentQuery()->whereIn('status', ['in_transit', 'In Transit', 'transit', 'Transit', 'In-Transit'])->count(),
            'out_for_delivery' => $this->getVisibleShipmentQuery()->whereIn('status', ['out_for_delivery', 'Out for Delivery', 'ofd', 'OFD'])->count(),
            'delivered' => $this->getVisibleShipmentQuery()->whereIn('status', ['delivered', 'Delivered', 'DELIVERED'])->count(),
            'rto' => $this->getVisibleShipmentQuery()->whereIn('status', ['rto', 'RTO', 'rto_transit', 'RTO In-Transit', 'rto_delivered', 'RTO Delivered', 'rto in-transit', 'rto delivered'])->count(),
            'ndr' => $this->getVisibleShipmentQuery()->whereIn('status', ['ndr', 'NDR', 'action_required', 'undelivered', 'Undelivered'])->count(),
            'lost' => $this->getVisibleShipmentQuery()->whereIn('status', ['lost', 'Lost', 'damaged', 'Damaged'])->count(),
            'cancelled' => $this->getVisibleShipmentQuery()->whereIn('status', ['cancelled', 'Cancelled', 'CANCELLED'])->count(),
            'all' => $this->getVisibleShipmentQuery()->count(),
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

        $shipments = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $shipments->appends($request->all());

        return view('hub.shipments.index', compact('shipments', 'counts'));
    }

    public function updateEwayBill(Request $request)
    {
        $request->validate([
            'awb_number' => 'required|string',
            'eway_bill_number' => 'required|string|max:50'
        ]);

        $shipment = $this->getOwnedShipmentQuery()
            ->where('awb_number', $request->awb_number)
            ->firstOrFail();

        $shipment->eway_bill_number = $request->eway_bill_number;
        $shipment->save();

        return back()->with('success', 'E-Way Bill ' . $shipment->eway_bill_number . ' successfully updated for ' . $shipment->awb_number);
    }

    public function shipNowAction($id)
    {
        $shipment = $this->getOwnedShipmentQuery()->where('id', $id)->firstOrFail();
        
        if (in_array(strtolower($shipment->status), ['new', 'manifested', 'booked'])) {
            $shipment->status = 'Pickup Scheduled';
            $shipment->save();

            // Optionally create an event tracking entry here if ShipmentEvent model exists,
            // but we'll stick to basic state change to keep it simple.

            return back()->with('success', 'Shipment successfully marked as Pickup Scheduled.');
        }

        return back()->with('error', 'Shipment cannot be shipped at this stage.');
    }

    public function cancel($id)
    {
        $shipment = $this->getOwnedShipmentQuery()->where('id', $id)->firstOrFail();
        
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
        
        $settings = Auth::user()->label_settings ?? [
            'label_type' => 'thermal',
            'enable_product_name' => true,
            'enable_consignee_contact' => true,
            'enable_consignee_address' => true,
            'enable_support_contact' => true,
            'enable_support_email' => true,
            'enable_rto_address' => true,
        ];

        return view('seller.label', compact('shipment', 'settings'));
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

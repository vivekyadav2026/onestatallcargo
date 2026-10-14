<?php
namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        if ($user && $user->isAdmin()) {
            return \App\Models\Shipment::query();
        }

        $franchise = \App\Models\Franchise::where('user_id', $user->id)->first();
        $q = \App\Models\Shipment::query();
        if ($franchise) {
            if ($franchise->isState()) {
                $districtIds = $franchise->districtFranchises()->pluck('id')->toArray();
                $q->where(function($query) use ($franchise, $user, $districtIds) {
                    $query->where('state_franchise_id', $franchise->id)
                          ->orWhere('franchise_id', $franchise->id)
                          ->orWhere('pickup_franchise_id', $franchise->id)
                          ->orWhere('user_id', $user->id)
                          ->orWhere('delivery_state', $franchise->state);
                    if (!empty($districtIds)) {
                        $query->orWhereIn('franchise_id', $districtIds)
                              ->orWhereIn('pickup_franchise_id', $districtIds);
                    }
                });
            } else {
                $pincodes = is_array($franchise->serviceable_pincodes) ? $franchise->serviceable_pincodes : (json_decode($franchise->serviceable_pincodes, true) ?? []);
                if (!is_array($pincodes)) { $pincodes = $pincodes ? [$pincodes] : []; }
                
                $q->where(function($query) use ($franchise, $user, $pincodes) {
                    $query->where('franchise_id', $franchise->id)
                          ->orWhere('pickup_franchise_id', $franchise->id)
                          ->orWhere('user_id', $user->id);
                    if (!empty($pincodes)) {
                        $query->orWhereIn('pickup_pincode', $pincodes)->orWhereIn('delivery_pincode', $pincodes);
                    }
                });
            }
        } else {
            $hub = \App\Models\Hub::where('manager_id', $user->id)->first();
            if ($hub) {
                $q->where(function($query) use ($hub, $user) {
                    $query->where('user_id', $user->id)
                          ->orWhere('pickup_pincode', $hub->pincode)
                          ->orWhere('delivery_pincode', $hub->pincode);
                });
            } else {
                $q->where('user_id', $user->id);
            }
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
            'order_id' => 'nullable|string|max:255',
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'receiver_email' => 'nullable|email|max:255',
            'delivery_address' => 'required|string|max:1000',
            'delivery_landmark' => 'nullable|string|max:255',
            'delivery_city' => 'required|string|max:255',
            'delivery_state' => 'nullable|string|max:255',
            'delivery_pincode' => 'required|string|max:10',
            'pickup_pincode' => 'nullable|string|max:10',
            'pickup_address' => 'nullable|string|max:1000',
            'pickup_city' => 'nullable|string|max:255',
            'destination_country' => 'nullable|string|max:255',
            'customs_value' => 'nullable|numeric',
            'hs_code' => 'nullable|string|max:50',
            'vehicle_type' => 'nullable|string|max:255',
            'weight_kg' => 'required|numeric',
            'length_cm' => 'nullable|numeric',
            'width_cm' => 'nullable|numeric',
            'height_cm' => 'nullable|numeric',
            'payment_mode' => 'nullable|string',
            'cod_amount' => 'nullable|numeric',
            'is_cod' => 'nullable|boolean',
            'invoice_value' => 'nullable|numeric',
        ]);

        $user = Auth::user();

        // KYC Check: Shipment cannot be booked/shipped without approved KYC
        $kyc = \App\Models\Kyc::where('user_id', $user->id)->first();
        if (!$kyc || $kyc->status !== 'approved') {
            return redirect()->route('hub.profile')
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
        $is_cod = ($request->input('payment_mode') === 'COD' || $request->input('is_cod') == '1');
        $codOrInvoiceVal = (float)($request->input('cod_amount') ?? $validated['invoice_value'] ?? 0);

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
                    $is_cod,
                    $codOrInvoiceVal,
                    false, false, 0,
                    $routing['provider_id']
                );
                $totalAmount = $rateData['total'];
            } catch (\Exception $e) {
                $totalAmount = max(50, round((float)$validated['weight_kg'] * 60, 2));
            }
        }

        try {
            DB::transaction(function () use ($user, $isEdit, $shipment, $totalAmount, $validated, $request, $is_cod, $isInternational) {
                if (!$isEdit) {
                    app(\App\Services\WalletService::class)->deduct(
                        $user->id, 
                        $totalAmount, 
                        'Shipment Booking Deduction', 
                        $shipment->awb_number ?? null
                    );
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
                $shipment->length_cm = $request->input('length_cm');
                $shipment->width_cm = $request->input('width_cm');
                $shipment->height_cm = $request->input('height_cm');
                $shipment->order_id = $request->input('order_id');
                $shipment->receiver_email = $request->input('receiver_email');
                $shipment->delivery_landmark = $request->input('delivery_landmark');
                $shipment->delivery_state = $request->input('delivery_state');
                $shipment->is_cod = $is_cod;
                $shipment->payment_type = $is_cod ? 'COD' : 'Prepaid';
                $shipment->cod_amount = $request->input('cod_amount') ?? ($is_cod ? ($validated['invoice_value'] ?? 0) : null);
                $shipment->invoice_value = $validated['invoice_value'] ?? 0;

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
                    $shipment->payment_type = 'Prepaid';
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
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        if ($isEdit) {
            return redirect()->route('hub.shipments')->with('success', 'Order updated successfully.');
        }

        return redirect()->route('hub.shipments')->with('success', "Order Booked! Forwarded to {$shipment->courier_partner} (AWB: {$shipment->awb_number}).");
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

    public function getCouriers($id)
    {
        $shipment = $this->getOwnedShipmentQuery()->where('id', $id)->firstOrFail();
        
        $serviceability = app(\App\Services\ServiceabilityService::class)->determineRouting($shipment->delivery_pincode);
        
        $pricing = app(\App\Services\PricingService::class);
        $w = (float)($shipment->width_cm ?? 10);
        $h = (float)($shipment->height_cm ?? 10);
        $l = (float)($shipment->length_cm ?? 10);
        $physicalWeight = (float)($shipment->weight_kg ?? 0.5);
        
        $is_cod = $shipment->is_cod == 1;
        $invoice_value = (float)($shipment->invoice_value ?? 0);
        
        $results = [];

        if ($serviceability['fulfillment_type'] === 'onestall') {
            // Only show Onestall
            try {
                $rate = $pricing->calculateOneStallRate(
                    $shipment->pickup_pincode ?? '110001', 
                    $shipment->delivery_pincode ?? '110001', 
                    $physicalWeight, 
                    $l, $w, $h, 
                    $is_cod, 
                    $invoice_value
                );
                
                $results[] = [
                    'name' => 'OneStall Cargo',
                    'transport_mode' => 'Surface',
                    'eta' => '1-2 Days',
                    'rating' => '5.0',
                    'rate' => round($rate['total'] ?? 0, 2),
                    'recommended' => true
                ];
            } catch (\Exception $e) {
                // Fallback to basic rate
                $chargeableWeight = max($physicalWeight, ($l * $w * $h) / 5000);
                $results[] = [
                    'name' => 'OneStall Cargo',
                    'transport_mode' => 'Surface',
                    'eta' => '1-2 Days',
                    'rating' => '5.0',
                    'rate' => round((1150 + ($chargeableWeight * 420)) * 1.18, 2),
                    'recommended' => true
                ];
            }
        } else {
            // Show only active 3rd party
            $couriers = \App\Models\Courier::where('is_active', true)->where('name', '!=', 'Onestall Cargo')->get();
            
            foreach ($couriers as $c) {
                try {
                    $rc = \App\Models\RateCard::where('courier_id', $c->id)->where('is_active', true)->latest()->first();
                    $volDivisor = $rc ? $rc->volumetric_divisor : 5000;
                    $cw = max($physicalWeight, ($l * $w * $h) / $volDivisor);

                    $rate = $pricing->calculateExternalRate(
                        $c->id, 
                        $shipment->pickup_pincode ?? '110001', 
                        $shipment->delivery_pincode ?? '110001', 
                        $cw, 
                        $is_cod, 
                        $invoice_value
                    );
                    
                    $results[] = [
                        'name' => $c->name,
                        'transport_mode' => $c->transport_mode ?? 'Surface',
                        'eta' => $c->eta_days ? $c->eta_days . ' Days' : '3-5 Days',
                        'rating' => $c->rating ?? '4.0',
                        'rate' => round($rate['total'] ?? $rate['base_freight'] ?? 0, 2),
                        'recommended' => false
                    ];
                } catch (\Exception $e) {
                    continue; // Skip couriers that fail pricing calculation
                }
            }
            
            // Mark the cheapest as recommended
            if (count($results) > 0) {
                usort($results, fn($a, $b) => $a['rate'] <=> $b['rate']);
                $results[0]['recommended'] = true;
            }
        }

        return response()->json($results);
    }

    public function shipNowAction(Request $request, $id)
    {
        $shipment = $this->getOwnedShipmentQuery()->where('id', $id)->firstOrFail();
        
        if (in_array(strtolower($shipment->status), ['new', 'manifested', 'booked'])) {
            $newCharge = $request->input('shipping_charge');
            $courier = $request->input('courier_partner');
            
            if ($newCharge !== null && $courier) {
                $oldCharge = $shipment->shipping_charge ?? $shipment->total_amount ?? 0;
                $diff = (float)$newCharge - (float)$oldCharge;
                
                if ($diff > 0) {
                    try {
                        app(\App\Services\WalletService::class)->deduct(
                            Auth::id(), 
                            $diff, 
                            'Courier Upgrade Upcharge: ' . $courier, 
                            $shipment->awb_number
                        );
                    } catch (\Exception $e) {
                        return back()->with('error', $e->getMessage());
                    }
                } elseif ($diff < 0) {
                    app(\App\Services\WalletService::class)->credit(
                        Auth::id(), 
                        abs($diff), 
                        'Courier Downgrade Savings: ' . $courier, 
                        $shipment->awb_number
                    );
                }
                
                $shipment->shipping_charge = $newCharge;
                $shipment->total_amount = $newCharge;
                $shipment->courier_partner = $courier;
            }

            $shipment->status = 'Pickup Scheduled';
            $shipment->save();

            $remarks = $courier ? ('Shipment assigned to ' . $courier) : 'Shipment successfully marked for pickup.';
            \App\Models\ShipmentEvent::create([
                'shipment_id' => $shipment->id,
                'status' => 'Pickup Scheduled',
                'location' => $shipment->pickup_city ?? 'Origin Hub',
                'remarks' => $remarks
            ]);

            $successMsg = $courier 
                ? ('Shipment successfully assigned to ' . $courier . ' and marked for pickup.')
                : 'Shipment successfully marked as Pickup Scheduled.';

            return back()->with('success', $successMsg);
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

    /**
     * Strictly authorize shipment access for the authenticated franchise/hub user.
     * Prevents cross-district access, enforces state franchise boundaries, and allows Super Admin.
     */
    public function authorizeShipmentAccess(string $awb): Shipment
    {
        $shipment = Shipment::where('awb_number', $awb)->first();
        if (!$shipment) {
            abort(404, 'Shipment not found.');
        }

        $user = Auth::user();
        if ($user && $user->isAdmin()) {
            return $shipment;
        }

        $isAuthorized = $this->getVisibleShipmentQuery()->where('awb_number', $awb)->exists();
        if (!$isAuthorized) {
            abort(403, 'Unauthorized: You do not have permission to access the shipment slip for this order.');
        }

        return $shipment;
    }

    public function printLabel(Request $request, $awb)
    {
        $shipment = $this->authorizeShipmentAccess($awb);
        
        $settings = Auth::user()->label_settings ?? [
            'label_type' => 'thermal',
            'enable_product_name' => true,
            'enable_consignee_contact' => true,
            'enable_consignee_address' => true,
            'enable_support_contact' => true,
            'enable_support_email' => true,
            'enable_rto_address' => true,
        ];

        // Generate Real Barcode safely with error handling
        $barcode = null;
        try {
            if (class_exists(\Picqer\Barcode\BarcodeGeneratorSVG::class)) {
                $generator = new \Picqer\Barcode\BarcodeGeneratorSVG();
                $barcode = $generator->getBarcode($shipment->awb_number, $generator::TYPE_CODE_128, 2, 60);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Barcode generation failed for {$shipment->awb_number}: " . $e->getMessage());
            $barcode = null;
        }

        // Gracefully handle optional PDF download if requested and dependency present
        if ($request->query('download') === 'pdf' || $request->query('format') === 'pdf') {
            if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                try {
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('seller.label', compact('shipment', 'settings', 'barcode'));
                    return $pdf->download("shipment-slip-{$shipment->awb_number}.pdf");
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error("PDF generation failed for {$shipment->awb_number}: " . $e->getMessage());
                }
            }
        }

        return view('seller.label', compact('shipment', 'settings', 'barcode'));
    }

    public function printLR(Request $request, $awb)
    {
        $shipment = $this->authorizeShipmentAccess($awb);
        return view('seller.lr', compact('shipment'));
    }

    public function printInvoice(Request $request, $awb)
    {
        $shipment = $this->authorizeShipmentAccess($awb);
        return view('seller.invoice', compact('shipment'));
    }
}

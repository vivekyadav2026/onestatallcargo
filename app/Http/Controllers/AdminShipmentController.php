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

        // Quick Date preset resolution
        if ($request->filled('quick_date') && (!$request->filled('start_date') || !$request->filled('end_date'))) {
            $today = now()->toDateString();
            if ($request->quick_date === 'today') {
                $request->merge(['start_date' => $today, 'end_date' => $today]);
            } elseif ($request->quick_date === 'yesterday') {
                $yesterday = now()->subDay()->toDateString();
                $request->merge(['start_date' => $yesterday, 'end_date' => $yesterday]);
            } elseif ($request->quick_date === '7days') {
                $request->merge(['start_date' => now()->subDays(6)->toDateString(), 'end_date' => $today]);
            } elseif ($request->quick_date === '30days') {
                $request->merge(['start_date' => now()->subDays(29)->toDateString(), 'end_date' => $today]);
            } elseif ($request->quick_date === 'this_month') {
                $request->merge(['start_date' => now()->startOfMonth()->toDateString(), 'end_date' => $today]);
            }
        }

        // Date range filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        } elseif ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date . ' 00:00:00');
        } elseif ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }

        // Payment mode filter
        if ($request->filled('payment_mode')) {
            if ($request->payment_mode === 'cod') {
                $query->where('is_cod', 1);
            } elseif ($request->payment_mode === 'prepaid') {
                $query->where('is_cod', 0);
            }
        }

        // Courier partner filter
        if ($request->filled('courier') && $request->courier !== 'all') {
            $query->where('courier_partner', $request->courier);
        }

        $couriers = \App\Models\Courier::where('is_active', true)->pluck('name');

        $shipments = $query->paginate(15)->withQueryString();

        return view('admin.shipments.index', compact('shipments', 'couriers'));
    }

    public function create()
    {
        $sellers = \App\Models\User::whereIn('role', ['seller', 'aggregator', 'corporate', 'b2b_customer'])->get();
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

        try {
            app(\App\Services\WalletService::class)->deduct(
                $validated['user_id'],
                $totalAmount,
                'Admin Manual Booking Deduction'
            );
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
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

    public function printLabel(Request $request, $awb)
    {
        $shipment = Shipment::where('awb_number', $awb)->firstOrFail();
        
        $settings = [
            'label_type' => 'thermal',
            'enable_product_name' => true,
            'enable_consignee_contact' => true,
            'enable_consignee_address' => true,
            'enable_support_contact' => true,
            'enable_support_email' => true,
            'enable_rto_address' => true,
        ];

        // Generate Real Barcode safely
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
        $shipment = Shipment::where('awb_number', $awb)->firstOrFail();
        return view('seller.lr', compact('shipment'));
    }

    public function printInvoice(Request $request, $awb)
    {
        $shipment = Shipment::where('awb_number', $awb)->firstOrFail();
        return view('seller.invoice', compact('shipment'));
    }
}

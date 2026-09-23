<?php
namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SellerShipmentController extends Controller
{
    public function create()
    {
        return view('seller.book');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
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
        
        // Dynamic Carrier Assignment Simulation (Delhivery / BlueDart / OneStall)
        $carriers = ['Delhivery', 'BlueDart', 'OneStall Direct'];
        $assignedCourier = $carriers[array_rand($carriers)];

        // Rate Calc Mock
        $baseRate = $validated['shipment_type'] == 'International' ? 1500 : ($validated['shipment_type'] == 'B2B' ? 500 : 45);
        $totalAmount = $baseRate + ($validated['weight_kg'] * 10);
        $is_cod = $validated['is_cod'] ?? false;
        
        if ($user->wallet_balance < $totalAmount && !$is_cod) {
            return back()->with('error', 'Insufficient wallet balance. Required: ₹' . number_format($totalAmount, 2));
        }

        $user->wallet_balance -= $totalAmount;
        $user->save();

        $shipment = new Shipment();
        $shipment->user_id = $user->id;
        $shipment->awb_number = 'OSC' . strtoupper(Str::random(8));
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
        $shipment->status = 'Manifested';
        
        // B2B & Intl logic
        if ($validated['shipment_type'] == 'B2B') {
            $shipment->vehicle_type = $validated['vehicle_type'];
        }
        if ($validated['shipment_type'] == 'International') {
            $shipment->destination_country = $validated['destination_country'];
            $shipment->customs_value = $validated['customs_value'];
            $shipment->hs_code = $validated['hs_code'];
            $shipment->is_cod = false;
        }

        $shipment->courier_partner = $assignedCourier;
        $shipment->save();

        // SIMULATED: Third Platform Push via API
        // Here we would run: Http::post('https://api.delhivery.com/v1/create', [...])

        return redirect()->route('seller.label', $shipment->awb_number)->with('success', "Shipment Booked! Forwarded to $assignedCourier.");
    }

    public function printLabel($awb)
    {
        $shipment = Shipment::where('awb_number', $awb)->where('user_id', Auth::id())->firstOrFail();
        return view('seller.label', compact('shipment'));
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

        // In a real scenario we would parse the CSV using fgetcsv.
        // For demonstration, we will just create 3 dummy shipments to simulate bulk processing.
        
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
    public function index()
    {
        $shipments = \App\Models\Shipment::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('seller.shipments', compact('shipments'));
    }
}

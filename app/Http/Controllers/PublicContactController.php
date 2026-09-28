<?php

namespace App\Http\Controllers;

use App\Models\ContactLead;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use Illuminate\Http\Request;

class PublicContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'volume'       => 'nullable|string|max:100',
            'message'      => 'required|string|max:2000',
        ]);

        ContactLead::create($validated);

        return back()->with('success', 'Thank you for contacting us! Our team will get back to you shortly.');
    }

    public function resolveNdr($awb)
    {
        $shipment = Shipment::where('awb_number', $awb)
                            ->where('status', 'NDR')
                            ->firstOrFail();
        return view('ndr_resolve', compact('shipment'));
    }

    public function submitResolveNdr(Request $request, $awb)
    {
        $request->validate([
            'customer_action' => 'required|in:reattempt,rto',
        ]);

        $shipment = Shipment::where('awb_number', $awb)
                            ->where('status', 'NDR')
                            ->firstOrFail();

        if ($request->customer_action === 'reattempt') {
            $shipment->status     = 'Out for Delivery';
            $shipment->ndr_action = 'Customer Requested Re-attempt';
        } else {
            $shipment->status     = 'RTO Initiated';
            $shipment->ndr_action = 'Customer Refused';
        }

        $shipment->save();

        ShipmentEvent::create([
            'shipment_id' => $shipment->id,
            'status'      => $shipment->status,
            'location'    => $shipment->delivery_city,
            'remarks'     => $shipment->ndr_action,
        ]);

        return back()->with('success', 'Thank you! Your response has been recorded.');
    }
}

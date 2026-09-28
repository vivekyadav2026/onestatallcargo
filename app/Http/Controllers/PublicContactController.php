<?php
namespace App\Http\Controllers;

use App\Models\ContactLead;
use Illuminate\Http\Request;

class PublicContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'volume' => 'nullable|string|max:100',
            'message' => 'required|string|max:2000',
        ]);

        ContactLead::create($validated);

        return back()->with('success', 'Thank you for contacting us! Our team will get back to you shortly.');
    
    public function resolveNdr(\)
    {
        \ = \App\Models\Shipment::where('awb_number', \)->where('status', 'NDR')->firstOrFail();
        return view('ndr_resolve', compact('shipment'));
    }

    public function submitResolveNdr(\Illuminate\Http\Request \, \)
    {
        \->validate([
            'customer_action' => 'required|in:reattempt,rto'
        ]);

        \ = \App\Models\Shipment::where('awb_number', \)->where('status', 'NDR')->firstOrFail();
        
        if (\->customer_action === 'reattempt') {
            \->status = 'Out for Delivery';
            \->ndr_action = 'Customer Requested Re-attempt';
        } else {
            \->status = 'RTO Initiated';
            \->ndr_action = 'Customer Refused';
        }
        
        \->save();
        
        // Log event
        \App\Models\ShipmentEvent::create([
            'shipment_id' => \->id,
            'status' => \->status,
            'location' => \->delivery_city,
            'remarks' => \->ndr_action
        ]);

        return back()->with('success', 'Thank you! Your response has been recorded.');
    }
}
    public function resolveNdr(\)
    {
        \ = \App\Models\Shipment::where('awb_number', \)->where('status', 'NDR')->firstOrFail();
        return view('ndr_resolve', compact('shipment'));
    }

    public function submitResolveNdr(\Illuminate\Http\Request \, \)
    {
        \->validate([
            'customer_action' => 'required|in:reattempt,rto'
        ]);

        \ = \App\Models\Shipment::where('awb_number', \)->where('status', 'NDR')->firstOrFail();
        
        if (\->customer_action === 'reattempt') {
            \->status = 'Out for Delivery';
            \->ndr_action = 'Customer Requested Re-attempt';
        } else {
            \->status = 'RTO Initiated';
            \->ndr_action = 'Customer Refused';
        }
        
        \->save();
        
        // Log event
        \App\Models\ShipmentEvent::create([
            'shipment_id' => \->id,
            'status' => \->status,
            'location' => \->delivery_city,
            'remarks' => \->ndr_action
        ]);

        return back()->with('success', 'Thank you! Your response has been recorded.');
    }
}

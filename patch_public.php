<?php
$file = 'app/Http/Controllers/PublicContactController.php';
$content = file_get_contents($file);

$methods = <<<EOD
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
EOD;

$content = str_replace("}\n", "\n" . $methods, $content);
file_put_contents($file, $content);
echo "Patched PublicContactController\n";

<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;

class AdminNDRController extends Controller 
{
    public function index() 
    {
        $shipments = Shipment::whereIn('status', ['NDR', 'RTO Initiated'])
                        ->with('user')
                        ->orderBy('updated_at', 'desc')
                        ->paginate(15);
        return view('admin.ndr.index', compact('shipments'));
    }

    public function action(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_action' => 'required|in:reattempt,rto,rto_delivered'
        ]);

        $shipment = Shipment::findOrFail($id);

        if ($validated['admin_action'] === 'reattempt') {
            $shipment->status = 'Out for Delivery';
            $shipment->ndr_action = null; // Clear the pending seller action
            $shipment->save();
            return back()->with('success', 'Shipment ' . $shipment->awb_number . ' pushed back to Out for Delivery.');
        }

        if ($validated['admin_action'] === 'rto') {
            $shipment->status = 'RTO Initiated';
            $shipment->save();
            return back()->with('success', 'RTO officially initiated for ' . $shipment->awb_number . '.');
        }
        
        if ($validated['admin_action'] === 'rto_delivered') {
            $shipment->status = 'RTO Delivered';
            $shipment->save();
            return back()->with('success', 'Shipment ' . $shipment->awb_number . ' successfully returned to Seller.');
        }
        
        return back();
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerNdrController extends Controller
{
    public function index()
    {
        $ndrShipments = Shipment::where('user_id', Auth::id())
            ->where('status', 'NDR')
            ->orderBy('updated_at', 'desc')
            ->get();
            
        return view('seller.ndr', compact('ndrShipments'));
    }

    public function action(Request $request, $awb)
    {
        $validated = $request->validate([
            'ndr_action' => 'required|string|in:Re-attempt,RTO,Hold'
        ]);

        $shipment = Shipment::where('awb_number', $awb)->where('user_id', Auth::id())->firstOrFail();
        $shipment->ndr_action = $validated['ndr_action'];
        
        if ($validated['ndr_action'] == 'Re-attempt') {
            $shipment->status = 'Out for Delivery'; // Push back to rider
        } elseif ($validated['ndr_action'] == 'RTO') {
            $shipment->status = 'RTO Initiated';
        }
        
        $shipment->save();

        return back()->with('success', 'NDR Action submitted for ' . $awb);
    }
}

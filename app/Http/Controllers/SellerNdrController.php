<?php
namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerNdrController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'action_required');

        $query = Shipment::where('user_id', Auth::id());

        if ($tab === 'action_required') {
            $query->where('status', 'NDR')->whereNull('ndr_action');
        } elseif ($tab === 'action_taken') {
            $query->where('status', 'NDR')->whereNotNull('ndr_action');
        } elseif ($tab === 'delivered') {
            $query->where('status', 'Delivered')->whereNotNull('ndr_action'); // Was NDR but now delivered
        } elseif ($tab === 'rto') {
            $query->whereIn('status', ['RTO Initiated', 'RTO Delivered']);
        } elseif ($tab === 'all') {
            $query->where(function ($q) {
                $q->where('status', 'NDR')
                  ->orWhereIn('status', ['RTO Initiated', 'RTO Delivered']);
            });
        }

        $ndrShipments = $query->orderBy('updated_at', 'desc')->paginate(15)->appends($request->all());
            
        return view('seller.ndr', compact('ndrShipments', 'tab'));
    }

    public function action(Request $request, $awb)
    {
        $validated = $request->validate([
            'ndr_action' => 'required|string|in:Re-attempt,RTO,Hold'
        ]);

        $shipment = Shipment::where('awb_number', $awb)->where('user_id', Auth::id())->firstOrFail();
        
        // Only update the ndr_action, leave status as NDR so it moves to "Action Taken" tab
        $shipment->ndr_action = $validated['ndr_action'];
        $shipment->save();

        return back()->with('success', 'NDR Action (' . $validated['ndr_action'] . ') submitted for ' . $awb);
    }
}

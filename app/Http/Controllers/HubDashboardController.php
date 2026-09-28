<?php
namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HubDashboardController extends Controller
{
    public function index()
    {
        $recentScans = Shipment::orderBy('updated_at', 'desc')->take(10)->get();
        $riders = \App\Models\User::where('role', 'rider')->get();
        $kyc = \App\Models\Kyc::where('user_id', Auth::id())->first();
        return view('hub.dashboard', compact('recentScans', 'riders', 'kyc'));
    }

    public function scan(Request $request)
    {
        $validated = $request->validate([
            'awb_number' => 'required|string',
            'action' => 'required|string|in:Receive,Dispatch,Out for Delivery',
            'rider_id' => 'nullable|exists:users,id'
        ]);

        $shipment = Shipment::where('awb_number', $validated['awb_number'])->first();

        if (!$shipment) {
            return back()->with('error', 'Shipment not found for AWB: ' . $validated['awb_number']);
        }

        $shipment->status = $validated['action'];
        
        if (!empty($validated['rider_id'])) {
            $shipment->rider_id = $validated['rider_id'];
        }

        $shipment->save();

        \App\Models\ShipmentEvent::create([
            'shipment_id' => $shipment->id,
            'status' => $validated['action'],
            'location' => $shipment->delivery_city ?? 'Hub',
            'remarks' => 'Hub scanned package: ' . $validated['action']
        ]);

        return back()->with('success', 'Shipment ' . $validated['awb_number'] . ' marked as: ' . $validated['action']);
    }
}

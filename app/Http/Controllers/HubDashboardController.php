<?php
namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HubDashboardController extends Controller
{
    public function index()
    {
        // Hubs will primarily scan packages and build bags.
        // For dashboard, we can just show recent activity in this Hub's city/zone (mocked for now).
        $recentScans = Shipment::orderBy('updated_at', 'desc')->take(10)->get();
        return view('hub.dashboard', compact('recentScans'));
    }

    public function scan(Request $request)
    {
        $validated = $request->validate([
            'awb_number' => 'required|string',
            'action' => 'required|string|in:Receive,Dispatch,Out for Delivery'
        ]);

        $shipment = Shipment::where('awb_number', $validated['awb_number'])->first();

        if (!$shipment) {
            return back()->with('error', 'Shipment not found for AWB: ' . $validated['awb_number']);
        }

        $shipment->status = $validated['action'];
        $shipment->save();

        // Normally we would create a ShipmentEvent entry here.

        return back()->with('success', 'Shipment ' . $validated['awb_number'] . ' marked as: ' . $validated['action']);
    }
}

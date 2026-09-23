<?php
namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminPickupController extends Controller
{
    public function index(Request $request)
    {
        // Get all pending pickups (Manifested status)
        $pendingPickups = Shipment::where('status', 'Manifested')
                            ->with(['user', 'assignedRider'])
                            ->orderBy('created_at', 'desc')
                            ->get();

        // Get all pickup riders
        $riders = User::whereIn('role', ['rider', 'pickup_rider'])->get();

        return view('admin.pickups.index', compact('pendingPickups', 'riders'));
    }

    public function assignRider(Request $request)
    {
        $validated = $request->validate([
            'shipment_ids' => 'required|array',
            'rider_id' => 'required|exists:users,id'
        ]);

        Shipment::whereIn('id', $validated['shipment_ids'])
                ->update(['assigned_rider_id' => $validated['rider_id']]);

        return back()->with('success', count($validated['shipment_ids']) . ' shipments assigned to rider successfully.');
    }
}

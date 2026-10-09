<?php
namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminPickupController extends Controller
{
    public function index(Request $request)
    {
        $query = Shipment::whereIn('status', ['Manifested', 'Pickup Scheduled'])->with(['user', 'assignedRider']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('awb_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQ) use ($search) {
                      $userQ->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('rider_id')) {
            if ($request->rider_id == 'unassigned') {
                $query->whereNull('rider_id');
            } else {
                $query->where('rider_id', $request->rider_id);
            }
        }

        $pendingPickups = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Get all pickup riders
        $riders = User::whereIn('role', ['rider', 'pickup_rider', 'delivery_rider'])->get();

        return view('admin.pickups.index', compact('pendingPickups', 'riders'));
    }

    public function assignRider(Request $request)
    {
        $validated = $request->validate([
            'shipment_ids' => 'required|array',
            'rider_id' => 'required|exists:users,id'
        ]);

        Shipment::whereIn('id', $validated['shipment_ids'])
                ->update([
                    'rider_id' => $validated['rider_id'],
                    'status' => 'Pickup Scheduled'
                ]);

        foreach ($validated['shipment_ids'] as $id) {
            \App\Models\ShipmentEvent::create([
                'shipment_id' => $id,
                'status' => 'Pickup Scheduled',
                'location' => 'Origin',
                'remarks' => 'Admin assigned pickup rider'
            ]);
        }

        return back()->with('success', count($validated['shipment_ids']) . ' shipments assigned to rider successfully.');
    }
}

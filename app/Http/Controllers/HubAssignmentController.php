<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use App\Models\Rider;
use App\Models\Franchise;
use App\Models\Hub;

class HubAssignmentController extends Controller
{
    private function getAuthScope()
    {
        $user = Auth::user();
        $franchise = Franchise::where('user_id', $user->id)->first();
        $hubs = Hub::where('manager_id', $user->id)->get();

        if (!$franchise && $hubs->isEmpty()) abort(403, 'Unauthorized');

        return [
            'franchise_id' => $franchise ? $franchise->id : null,
            'hubs' => $hubs,
            'city' => $franchise ? $franchise->city : ($hubs->first() ? $hubs->first()->city : 'Hub')
        ];
    }

    private function filterByScope($query, $scope)
    {
        if ($scope['franchise_id']) {
            return $query->where('franchise_id', $scope['franchise_id']);
        } else {
            // Wait, shipments don't have hub_id by default, they have destination_hub_id or are just global without franchise_id
            return $query->whereNull('franchise_id'); // Internal shipments
        }
    }

    public function pickups()
    {
        $scope = $this->getAuthScope();
        
        $shipmentsQuery = Shipment::whereIn('status', ['Pending', 'Manifested']);
        $shipments = $this->filterByScope($shipmentsQuery, $scope)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $ridersQuery = Rider::with('user')->where('is_active', true);
        if ($scope['franchise_id']) {
            $ridersQuery->where('franchise_id', $scope['franchise_id']);
        } else {
            $ridersQuery->whereNull('franchise_id')->whereIn('hub_id', $scope['hubs']->pluck('id'));
        }
        $riders = $ridersQuery->get();

        return view('hub.assignments.pickups', compact('shipments', 'riders'));
    }

    public function deliveries()
    {
        $scope = $this->getAuthScope();
        
        $shipmentsQuery = Shipment::whereIn('status', ['Received', 'Out for Delivery']);
        $shipments = $this->filterByScope($shipmentsQuery, $scope)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $ridersQuery = Rider::with('user')->where('is_active', true);
        if ($scope['franchise_id']) {
            $ridersQuery->where('franchise_id', $scope['franchise_id']);
        } else {
            $ridersQuery->whereNull('franchise_id')->whereIn('hub_id', $scope['hubs']->pluck('id'));
        }
        $riders = $ridersQuery->get();

        return view('hub.assignments.deliveries', compact('shipments', 'riders'));
    }

    public function assign(Request $request)
    {
        $scope = $this->getAuthScope();
        
        $validated = $request->validate([
            'shipment_ids' => 'required|array',
            'shipment_ids.*' => 'exists:shipments,id',
            'rider_id' => 'required|exists:riders,id',
            'type' => 'required|in:pickup,delivery'
        ]);

        $rider = Rider::with('user')->findOrFail($validated['rider_id']);
        
        if ($scope['franchise_id']) {
            if ($rider->franchise_id !== $scope['franchise_id']) abort(403, 'Unauthorized');
        } else {
            if ($rider->franchise_id !== null || !in_array($rider->hub_id, $scope['hubs']->pluck('id')->toArray())) abort(403, 'Unauthorized');
        }

        return DB::transaction(function () use ($validated, $scope, $rider) {
            $assignedCount = 0;

            foreach ($validated['shipment_ids'] as $shipmentId) {
                $shipment = Shipment::lockForUpdate()->find($shipmentId);
                
                if ($scope['franchise_id']) {
                    if ($shipment->franchise_id !== $scope['franchise_id']) continue;
                } else {
                    if ($shipment->franchise_id !== null) continue;
                }

                if ($validated['type'] === 'pickup' && !in_array($shipment->status, ['Pending', 'Manifested', 'Pickup Scheduled'])) continue;
                if ($validated['type'] === 'delivery' && !in_array($shipment->status, ['Received', 'Out for Delivery'])) continue;

                $shipment->rider_id = $rider->user_id;
                $shipment->status = $validated['type'] === 'pickup' ? 'Pickup Scheduled' : 'Out for Delivery';
                $shipment->save();

                ShipmentEvent::create([
                    'shipment_id' => $shipment->id,
                    'status' => $shipment->status,
                    'location' => $scope['city'],
                    'remarks' => 'Assigned to ' . $rider->user->name
                ]);
                
                $assignedCount++;
            }

            return back()->with('success', $assignedCount . ' shipments successfully assigned to ' . $rider->user->name);
        });
    }
}

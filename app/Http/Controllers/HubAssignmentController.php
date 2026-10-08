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
            $franchise = \App\Models\Franchise::find($scope['franchise_id']);
            $pincodes = $franchise ? (is_array($franchise->serviceable_pincodes) ? $franchise->serviceable_pincodes : (json_decode($franchise->serviceable_pincodes, true) ?? [])) : [];
            if (!is_array($pincodes)) {
                $pincodes = $pincodes ? [$pincodes] : [];
            }
            return $query->where(function($q) use ($scope, $pincodes) {
                $q->where('franchise_id', $scope['franchise_id'])
                  ->orWhere('user_id', Auth::id());
                if (!empty($pincodes)) {
                    $q->orWhereIn('pickup_pincode', $pincodes)
                      ->orWhereIn('delivery_pincode', $pincodes);
                }
            });
        } else {
            // Internal Hub Manager - filter by Hub's city to prevent seeing the entire country's shipments
            return $query->whereNull('franchise_id')->where(function($q) use ($scope) {
                $q->where('pickup_city', $scope['city'])->orWhere('delivery_city', $scope['city']);
            });
        }
    }

    public function pickups()
    {
        $scope = $this->getAuthScope();
        
        $shipmentsQuery = Shipment::whereIn('status', ['Pending', 'Manifested']);
        $shipments = $this->filterByScope($shipmentsQuery, $scope)
            ->orderBy('created_at', 'desc')
            ->paginate(15)->withQueryString();
            
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
            ->paginate(15)->withQueryString();
            
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
                    $franchise = \App\Models\Franchise::find($scope['franchise_id']);
                    $pincodes = $franchise ? (is_array($franchise->serviceable_pincodes) ? $franchise->serviceable_pincodes : (json_decode($franchise->serviceable_pincodes, true) ?? [])) : [];
                    if (!is_array($pincodes)) $pincodes = $pincodes ? [$pincodes] : [];
                    $hasPincodeMatch = in_array($shipment->pickup_pincode, $pincodes) || in_array($shipment->delivery_pincode, $pincodes);
                    if ($shipment->franchise_id !== $scope['franchise_id'] && $shipment->user_id !== Auth::id() && !$hasPincodeMatch) {
                        continue;
                    }
                } else {
                    if ($shipment->pickup_city !== $scope['city'] && $shipment->delivery_city !== $scope['city']) {
                        continue;
                    }
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

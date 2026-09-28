<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use App\Models\Franchise;
use App\Models\Rider;
use App\Models\Hub;

class HubNdrController extends Controller
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

    public function index()
    {
        $scope = $this->getAuthScope();
        
        $shipmentsQuery = Shipment::where('status', 'NDR');
        if ($scope['franchise_id']) {
            $shipmentsQuery->where('franchise_id', $scope['franchise_id']);
        } else {
            $shipmentsQuery->whereNull('franchise_id');
        }
        $shipments = $shipmentsQuery->orderBy('updated_at', 'desc')->paginate(15);
            
        $ridersQuery = Rider::with('user')->where('is_active', true);
        if ($scope['franchise_id']) {
            $ridersQuery->where('franchise_id', $scope['franchise_id']);
        } else {
            $ridersQuery->whereNull('franchise_id')->whereIn('hub_id', $scope['hubs']->pluck('id'));
        }
        $riders = $ridersQuery->get();

        return view('hub.ndr.index', compact('shipments', 'riders'));
    }

    public function action(Request $request, $id)
    {
        $scope = $this->getAuthScope();
        
        $validated = $request->validate([
            'action' => 'required|in:reattempt,rto',
            'rider_id' => 'nullable|exists:riders,id',
            'remarks' => 'nullable|string|max:500'
        ]);

        return DB::transaction(function () use ($validated, $id, $scope) {
            $shipment = Shipment::lockForUpdate()->findOrFail($id);
            
            if ($scope['franchise_id']) {
                if ($shipment->franchise_id !== $scope['franchise_id']) abort(403, 'Unauthorized');
            } else {
                if ($shipment->franchise_id !== null) abort(403, 'Unauthorized');
            }

            if ($shipment->status !== 'NDR') {
                return back()->with('error', 'Shipment is no longer in NDR status.');
            }

            if ($validated['action'] === 'reattempt') {
                if (!$validated['rider_id']) return back()->with('error', 'Rider is required for reattempt.');
                
                $rider = Rider::with('user')->findOrFail($validated['rider_id']);
                if ($scope['franchise_id']) {
                    if ($rider->franchise_id !== $scope['franchise_id']) abort(403);
                } else {
                    if ($rider->franchise_id !== null || !in_array($rider->hub_id, $scope['hubs']->pluck('id')->toArray())) abort(403);
                }
                
                $shipment->status = 'Out for Delivery';
                $shipment->rider_id = $rider->user_id;
                $shipment->save();

                ShipmentEvent::create([
                    'shipment_id' => $shipment->id,
                    'status' => 'Out for Delivery',
                    'location' => $scope['city'],
                    'remarks' => 'NDR Reattempt Assigned to ' . $rider->user->name . '. ' . ($validated['remarks'] ?? '')
                ]);

                return back()->with('success', 'Shipment assigned for reattempt.');
            } else {
                $shipment->status = 'RTO';
                $shipment->rider_id = null;
                $shipment->save();

                ShipmentEvent::create([
                    'shipment_id' => $shipment->id,
                    'status' => 'RTO',
                    'location' => $scope['city'],
                    'remarks' => 'Marked as Return To Origin (RTO). ' . ($validated['remarks'] ?? '')
                ]);

                return back()->with('success', 'Shipment marked as RTO.');
            }
        });
    }
}

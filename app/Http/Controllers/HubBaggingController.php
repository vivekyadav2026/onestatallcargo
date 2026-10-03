<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Bag;
use App\Models\Manifest;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use App\Models\Hub;
use App\Models\Franchise;
use Illuminate\Support\Str;

class HubBaggingController extends Controller
{
    private function getAuthScope()
    {
        $user = Auth::user();
        
        $franchise = Franchise::where('user_id', $user->id)->first();
        if ($franchise && $franchise->status !== 'approved') {
            abort(403, 'Your franchise application is pending Admin approval.');
        }

        $hub = Hub::where('manager_id', $user->id)->first();
        
        if (!$hub && !$franchise) {
            abort(403, 'You are not assigned to any Hub or Franchise.');
        }

        return [
            'user' => $user,
            'franchise_id' => $franchise ? $franchise->id : null,
            'hub_id' => $hub ? $hub->id : null,
            'city' => $hub ? $hub->city : null,
        ];
    }

    private function authorizeBag($bag, $scope)
    {
        // STRICT ISOLATION: The bag MUST match the franchise_id or hub_id explicitly.
        $ownsFranchise = $scope['franchise_id'] && $bag->franchise_id === $scope['franchise_id'];
        $ownsHub = $scope['hub_id'] && $bag->hub_id === $scope['hub_id'];
        
        if (!$ownsFranchise && !$ownsHub) {
            abort(403, 'UNAUTHORIZED: You do not own this bag.');
        }
    }

    public function index()
    {
        $scope = $this->getAuthScope();
        
        $bags = Bag::withCount('shipments')
            ->where(function($q) use ($scope) {
                if ($scope['franchise_id']) $q->orWhere('franchise_id', $scope['franchise_id']);
                if ($scope['hub_id']) $q->orWhere('hub_id', $scope['hub_id']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $manifests = Manifest::where(function($q) use ($scope) {
                if ($scope['franchise_id']) $q->orWhere('franchise_id', $scope['franchise_id']);
                if ($scope['hub_id']) $q->orWhere('source_hub_id', $scope['hub_id']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $hubs = Hub::where('is_active', true)->get();

        return view('hub.bagging.index', compact('bags', 'manifests', 'hubs'));
    }

    public function storeBag(Request $request)
    {
        $scope = $this->getAuthScope();

        $validated = $request->validate([
            'destination_hub_id' => 'required|exists:hubs,id'
        ]);

        $destHub = Hub::find($validated['destination_hub_id']);
        if (!$destHub->is_active) {
            return back()->with('error', 'Destination Hub is inactive or unavailable.');
        }
        if ($destHub->id === $scope['hub_id']) {
            return back()->with('error', 'Destination cannot be the same as Source Hub.');
        }

        $bag = Bag::create([
            'bag_number' => 'BAG' . strtoupper(Str::random(8)) . time(),
            'hub_id' => $scope['hub_id'],
            'franchise_id' => $scope['franchise_id'],
            'destination_hub_id' => $validated['destination_hub_id'],
            'status' => 'OPEN'
        ]);

        return redirect()->route('hub.bagging.show', $bag->id)->with('success', 'Bag created successfully.');
    }

    public function showBag($id)
    {
        $scope = $this->getAuthScope();
        $bag = Bag::with(['shipments', 'destinationHub'])->findOrFail($id);
        
        $this->authorizeBag($bag, $scope);

        return view('hub.bagging.show', compact('bag'));
    }

    public function addShipment(Request $request, $id)
    {
        $scope = $this->getAuthScope();
        
        $validated = $request->validate([
            'awb_number' => 'required|string'
        ]);

        return DB::transaction(function () use ($validated, $id, $scope) {
            $bag = Bag::lockForUpdate()->findOrFail($id);
            $this->authorizeBag($bag, $scope);

            if ($bag->status !== 'OPEN') {
                return back()->with('error', 'Bag is sealed. Cannot add shipments.');
            }

            $shipment = Shipment::where('awb_number', $validated['awb_number'])->lockForUpdate()->first();

            if (!$shipment) {
                return back()->with('error', 'Shipment not found.');
            }

            // STRICT CUSTODY CHECK
            if ($scope['franchise_id']) {
                $franchise = \App\Models\Franchise::find($scope['franchise_id']);
                $pincodes = $franchise ? (is_array($franchise->serviceable_pincodes) ? $franchise->serviceable_pincodes : (json_decode($franchise->serviceable_pincodes, true) ?? [])) : [];
                if (!is_array($pincodes)) $pincodes = $pincodes ? [$pincodes] : [];
                $hasPincodeMatch = in_array($shipment->pickup_pincode, $pincodes) || in_array($shipment->delivery_pincode, $pincodes);
                
                if ($shipment->franchise_id !== $scope['franchise_id'] && $shipment->user_id !== Auth::id() && !$hasPincodeMatch) {
                    return back()->with('error', 'UNAUTHORIZED: Shipment is not mapped to your Franchise.');
                }
            } else {
                 if ($shipment->pickup_city !== $scope['city'] && $shipment->delivery_city !== $scope['city']) {
                     return back()->with('error', 'UNAUTHORIZED: Shipment is not mapped to your Hub.');
                 }
            }

            // STATE MACHINE CHECK
            if ($shipment->status !== 'Received') {
                return back()->with('error', 'Shipment status is [' . $shipment->status . ']. Only [Received] shipments can be bagged.');
            }

            if ($shipment->bag_id) {
                return back()->with('error', 'Shipment is already assigned to another bag.');
            }

            $shipment->bag_id = $bag->id;
            $shipment->save();

            ShipmentEvent::create([
                'shipment_id' => $shipment->id,
                'status' => 'Bagged',
                'location' => $scope['city'] ?? 'Hub',
                'remarks' => 'Added to Bag: ' . $bag->bag_number
            ]);

            return back()->with('success', 'Shipment added to Bag successfully.');
        });
    }

    public function removeShipment($bag_id, $shipment_id)
    {
        $scope = $this->getAuthScope();

        return DB::transaction(function () use ($bag_id, $shipment_id, $scope) {
            $bag = Bag::lockForUpdate()->findOrFail($bag_id);
            $this->authorizeBag($bag, $scope);

            if ($bag->status !== 'OPEN') {
                return back()->with('error', 'Bag is sealed. Cannot remove shipments.');
            }

            $shipment = Shipment::lockForUpdate()->findOrFail($shipment_id);

            if ($shipment->bag_id !== $bag->id) {
                return back()->with('error', 'Shipment does not belong to this bag.');
            }

            $shipment->bag_id = null;
            $shipment->save();

            ShipmentEvent::create([
                'shipment_id' => $shipment->id,
                'status' => 'Removed',
                'location' => $scope['city'] ?? 'Hub',
                'remarks' => 'Removed from Bag: ' . $bag->bag_number
            ]);

            return back()->with('success', 'Shipment removed from bag.');
        });
    }

    public function sealBag($id)
    {
        $scope = $this->getAuthScope();

        return DB::transaction(function () use ($id, $scope) {
            $bag = Bag::with('shipments')->lockForUpdate()->findOrFail($id);
            $this->authorizeBag($bag, $scope);

            if ($bag->status !== 'OPEN') {
                return back()->with('error', 'Bag is already sealed.');
            }

            if ($bag->shipments->count() === 0) {
                return back()->with('error', 'Cannot seal an empty bag.');
            }

            $totalWeight = $bag->shipments->sum('weight_kg');

            $bag->status = 'SEALED';
            $bag->sealed_by = $scope['user']->id;
            $bag->sealed_at = now();
            $bag->weight_kg = $totalWeight;
            $bag->save();

            return back()->with('success', 'Bag sealed successfully.');
        });
    }

    public function createManifest($bag_id)
    {
        $scope = $this->getAuthScope();

        return DB::transaction(function () use ($bag_id, $scope) {
            $bag = Bag::with('shipments')->lockForUpdate()->findOrFail($bag_id);
            $this->authorizeBag($bag, $scope);

            if ($bag->status !== 'SEALED') {
                return back()->with('error', 'Bag must be sealed before creating a manifest.');
            }

            $existing = Manifest::where('bag_id', $bag->id)->lockForUpdate()->first();
            if ($existing) {
                return back()->with('error', 'Manifest already exists for this bag.');
            }

            $manifest = Manifest::create([
                'manifest_number' => 'MF' . strtoupper(Str::random(10)),
                'bag_id' => $bag->id,
                'source_hub_id' => $scope['hub_id'],
                'franchise_id' => $scope['franchise_id'],
                'destination_hub_id' => $bag->destination_hub_id,
                'shipment_count' => $bag->shipments->count(),
                'status' => 'CREATED',
                'created_by' => $scope['user']->id,
            ]);

            return redirect()->route('hub.manifests.show', $manifest->id)->with('success', 'Manifest created successfully.');
        });
    }

    public function showManifest($id)
    {
        $scope = $this->getAuthScope();
        $manifest = Manifest::with(['bag.shipments', 'creator', 'destinationHub'])->findOrFail($id);

        $ownsFranchise = $scope['franchise_id'] && $manifest->franchise_id === $scope['franchise_id'];
        $ownsHub = $scope['hub_id'] && $manifest->source_hub_id === $scope['hub_id'];
        
        if (!$ownsFranchise && !$ownsHub) {
            abort(403, 'UNAUTHORIZED: You do not own this manifest.');
        }

        return view('hub.manifest.show', compact('manifest'));
    }

    public function printManifest($id)
    {
        $scope = $this->getAuthScope();
        $manifest = Manifest::with(['bag.shipments', 'creator', 'sourceHub', 'destinationHub'])->findOrFail($id);

        $ownsFranchise = $scope['franchise_id'] && $manifest->franchise_id === $scope['franchise_id'];
        $ownsHub = $scope['hub_id'] && $manifest->source_hub_id === $scope['hub_id'];
        
        if (!$ownsFranchise && !$ownsHub) {
            abort(403, 'UNAUTHORIZED');
        }

        return view('hub.manifest.print', compact('manifest'));
    }

    public function dispatchManifest($id)
    {
        $scope = $this->getAuthScope();

        return DB::transaction(function () use ($id, $scope) {
            $manifest = Manifest::lockForUpdate()->findOrFail($id);
            
            $ownsFranchise = $scope['franchise_id'] && $manifest->franchise_id === $scope['franchise_id'];
            $ownsHub = $scope['hub_id'] && $manifest->source_hub_id === $scope['hub_id'];
            
            if (!$ownsFranchise && !$ownsHub) {
                abort(403, 'UNAUTHORIZED');
            }

            if ($manifest->status === 'DISPATCHED') {
                return back()->with('error', 'Manifest is already dispatched.');
            }

            $bag = Bag::lockForUpdate()->findOrFail($manifest->bag_id);
            
            if ($bag->status === 'DISPATCHED') {
                return back()->with('error', 'Bag is already dispatched.');
            }

            $shipments = Shipment::where('bag_id', $bag->id)->lockForUpdate()->get();

            foreach ($shipments as $shipment) {
                if ($shipment->status !== 'Received') {
                    throw new \Exception('Shipment ' . $shipment->awb_number . ' is in an invalid state for dispatch: ' . $shipment->status);
                }
                
                $shipment->status = 'In Transit'; // Using the existing vocabulary
                $shipment->save();

                ShipmentEvent::create([
                    'shipment_id' => $shipment->id,
                    'status' => 'Dispatch',
                    'location' => $scope['city'] ?? 'Hub',
                    'remarks' => 'Dispatched in Bag: ' . $bag->bag_number . ' via Manifest: ' . $manifest->manifest_number
                ]);
            }

            $manifest->status = 'DISPATCHED';
            $manifest->dispatched_at = now();
            $manifest->save();

            $bag->status = 'DISPATCHED';
            $bag->dispatched_at = now();
            $bag->save();

            return back()->with('success', 'Manifest and all associated shipments dispatched successfully.');
        });
    }
}

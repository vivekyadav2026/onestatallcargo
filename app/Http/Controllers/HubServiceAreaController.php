<?php
namespace App\Http\Controllers;

use App\Models\Franchise;
use App\Models\Hub;
use App\Models\Shipment;
use App\Models\ServiceablePincode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HubServiceAreaController extends Controller
{
    private function getAuthScope()
    {
        $user = Auth::user();
        $franchise = Franchise::where('user_id', $user->id)->first();
        $isHubManager = Hub::where('manager_id', $user->id)->exists();
        
        if (!$franchise && !$isHubManager) {
            abort(403, 'Unauthorized');
        }

        return [
            'user' => $user,
            'franchise' => $franchise,
            'hub' => Hub::where('manager_id', $user->id)->first()
        ];
    }

    public function index()
    {
        $scope = $this->getAuthScope();
        $franchise = $scope['franchise'];
        $hub = $scope['hub'];
        
        $pincodes = [];
        $city = null;
        
        if ($franchise) {
            $pincodes = is_array($franchise->serviceable_pincodes) ? $franchise->serviceable_pincodes : (json_decode($franchise->serviceable_pincodes, true) ?? []);
            if (!is_array($pincodes)) { $pincodes = $pincodes ? [$pincodes] : []; }
            
            // Also check if any ServiceablePincode records are mapped directly
            $mappedPincodes = ServiceablePincode::where('franchise_id', $franchise->id)->pluck('pincode')->toArray();
            $pincodes = array_unique(array_merge($pincodes, $mappedPincodes));
        } elseif ($hub) {
            $city = $hub->city;
        }

        // We will build an array of stats per pincode (or city)
        $serviceAreas = [];

        if (!empty($pincodes)) {
            // Get stats for each pincode
            $pickupStats = Shipment::select('pickup_pincode', DB::raw('COUNT(*) as total'), DB::raw('SUM(CASE WHEN status IN ("Pending", "Pickup Scheduled") THEN 1 ELSE 0 END) as pending'))
                ->whereIn('pickup_pincode', $pincodes)
                ->groupBy('pickup_pincode')
                ->get()
                ->keyBy('pickup_pincode');

            $deliveryStats = Shipment::select('delivery_pincode', DB::raw('COUNT(*) as total'), DB::raw('SUM(CASE WHEN status IN ("Out for Delivery", "Transit", "In Transit", "Dispatched") THEN 1 ELSE 0 END) as pending'))
                ->whereIn('delivery_pincode', $pincodes)
                ->groupBy('delivery_pincode')
                ->get()
                ->keyBy('delivery_pincode');

            foreach ($pincodes as $pin) {
                $serviceAreas[] = [
                    'area_code' => $pin,
                    'type' => 'Pincode',
                    'pickups_total' => $pickupStats->has($pin) ? $pickupStats[$pin]->total : 0,
                    'pickups_pending' => $pickupStats->has($pin) ? $pickupStats[$pin]->pending : 0,
                    'deliveries_total' => $deliveryStats->has($pin) ? $deliveryStats[$pin]->total : 0,
                    'deliveries_pending' => $deliveryStats->has($pin) ? $deliveryStats[$pin]->pending : 0,
                ];
            }
        } elseif ($city) {
            $pickupStats = Shipment::select('pickup_city', DB::raw('COUNT(*) as total'), DB::raw('SUM(CASE WHEN status IN ("Pending", "Pickup Scheduled") THEN 1 ELSE 0 END) as pending'))
                ->where('pickup_city', $city)
                ->groupBy('pickup_city')
                ->first();

            $deliveryStats = Shipment::select('delivery_city', DB::raw('COUNT(*) as total'), DB::raw('SUM(CASE WHEN status IN ("Out for Delivery", "Transit", "In Transit", "Dispatched") THEN 1 ELSE 0 END) as pending'))
                ->where('delivery_city', $city)
                ->groupBy('delivery_city')
                ->first();

            $serviceAreas[] = [
                'area_code' => $city,
                'type' => 'City',
                'pickups_total' => $pickupStats ? $pickupStats->total : 0,
                'pickups_pending' => $pickupStats ? $pickupStats->pending : 0,
                'deliveries_total' => $deliveryStats ? $deliveryStats->total : 0,
                'deliveries_pending' => $deliveryStats ? $deliveryStats->pending : 0,
            ];
        }

        return view('hub.service_areas.index', compact('serviceAreas'));
    }
}

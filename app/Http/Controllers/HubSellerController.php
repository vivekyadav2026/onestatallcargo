<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Franchise;
use App\Models\Hub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HubSellerController extends Controller
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

    public function index(Request $request)
    {
        $scope = $this->getAuthScope();
        $franchise = $scope['franchise'];
        $hub = $scope['hub'];
        
        $pincodes = [];
        $city = null;
        if ($franchise) {
            $pincodes = is_array($franchise->serviceable_pincodes) ? $franchise->serviceable_pincodes : (json_decode($franchise->serviceable_pincodes, true) ?? []);
            if (!is_array($pincodes)) { $pincodes = $pincodes ? [$pincodes] : []; }
        } elseif ($hub) {
            $city = $hub->city;
        }

        $query = User::whereIn('role', ['seller', 'b2b_customer', 'corporate']);

        // Scope to Hub's operational area
        $query->where(function($q) use ($pincodes, $city) {
            if (!empty($pincodes)) {
                $q->whereIn('company_pincode', $pincodes)
                  ->orWhereHas('shipments', function($sq) use ($pincodes) {
                      $sq->whereIn('pickup_pincode', $pincodes);
                  });
            }
            if ($city) {
                $q->orWhere('company_city', $city)
                  ->orWhereHas('shipments', function($sq) use ($city) {
                      $sq->where('pickup_city', $city);
                  });
            }
        });

        // Apply Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('company_name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('company_pincode', 'LIKE', "%{$search}%");
            });
        }

        // Get counts
        $sellers = $query->withCount([
            'shipments as total_shipments',
            'shipments as pending_pickups' => function($q) {
                $q->whereIn('status', ['Pending', 'Pickup Scheduled']);
            }
        ])->orderBy('created_at', 'desc')->paginate(15)->appends($request->all());
        
        return view('hub.sellers.index', compact('sellers'));
    }
}

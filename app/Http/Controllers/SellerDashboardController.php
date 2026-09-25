<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $totalOrders = Shipment::where('user_id', $user->id)->count();
        $deliveredOrders = Shipment::where('user_id', $user->id)->whereIn('status', ['Delivered', 'delivered'])->count();
        $pickupsScheduled = Shipment::where('user_id', $user->id)->whereIn('status', ['pickup_scheduled', 'Pickup Scheduled', 'Booked', 'booked'])->count();
        $ndrCount = Shipment::where('user_id', $user->id)->whereIn('status', ['ndr', 'NDR', 'action_required'])->count();
        $weightDiscrepancies = 0;

        // COD of delivered items not yet remitted
        $codPending = Shipment::where('user_id', $user->id)
            ->where('is_cod', true)
            ->whereIn('status', ['Delivered', 'delivered'])
            ->sum('invoice_value');

        $recentShipments = Shipment::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $kyc = $user->kyc;

        return view('seller.dashboard', compact(
            'user', 
            'totalOrders', 
            'deliveredOrders', 
            'pickupsScheduled', 
            'ndrCount', 
            'weightDiscrepancies', 
            'codPending', 
            'recentShipments', 
            'kyc'
        ));
    }
}

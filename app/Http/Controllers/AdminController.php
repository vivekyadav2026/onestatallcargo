<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    private function checkAdminAccess()
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Unauthorized. You do not have admin access.');
        }
        return $user;
    }

    public function dashboard()
    {
        // Mock Data for OneStall Cargo Dashboard
        $totalShipments = 15420;
        $inTransit = 3450;
        $outForDelivery = 890;
        $deliveredToday = 1200;
        $pendingPickups = 450;
        $ndrCount = 120;
        $rtoCount = 45;

        $totalRevenue = 2540000;
        $totalSettlements = 1450000;

        // Mock Performance Table Data
        $courierPerformance = [
            ['name' => 'Delhivery', 'shipments' => 5400, 'delivered' => 5100, 'rto' => 150, 'accuracy' => 94],
            ['name' => 'Blue Dart', 'shipments' => 3200, 'delivered' => 3100, 'rto' => 50, 'accuracy' => 97],
            ['name' => 'Xpressbees', 'shipments' => 2800, 'delivered' => 2500, 'rto' => 200, 'accuracy' => 89],
            ['name' => 'OneStall Ground', 'shipments' => 4020, 'delivered' => 3900, 'rto' => 40, 'accuracy' => 97],
        ];

        // Weak PIN codes / NDR heavy zones
        $weakZones = [
            ['pincode' => '400001', 'city' => 'Mumbai', 'ndr_rate' => 12],
            ['pincode' => '110001', 'city' => 'Delhi', 'ndr_rate' => 8],
            ['pincode' => '560001', 'city' => 'Bangalore', 'ndr_rate' => 15],
        ];

        // Recent Bookings
        $recentShipments = [
            ['awb' => 'OSC10004561', 'seller' => 'TechMart', 'status' => 'In Transit'],
            ['awb' => 'OSC10004562', 'seller' => 'FashionHub', 'status' => 'Pending Pickup'],
            ['awb' => 'OSC10004563', 'seller' => 'TechMart', 'status' => 'Out For Delivery'],
            ['awb' => 'OSC10004564', 'seller' => 'GadgetPro', 'status' => 'Delivered'],
            ['awb' => 'OSC10004565', 'seller' => 'BooksIndia', 'status' => 'NDR'],
        ];

        return view('admin.dashboard', compact(
            'totalShipments',
            'inTransit',
            'outForDelivery',
            'deliveredToday',
            'pendingPickups',
            'ndrCount',
            'rtoCount',
            'totalRevenue',
            'totalSettlements',
            'courierPerformance',
            'weakZones',
            'recentShipments'
        ));
    }
    public function liveMap()
    {
        // Mocked active riders
        $activeRiders = \App\Models\User::whereIn('role', ['rider', 'pickup_rider', 'delivery_rider'])->take(5)->get();
        return view('admin.map', compact('activeRiders'));
    }
}

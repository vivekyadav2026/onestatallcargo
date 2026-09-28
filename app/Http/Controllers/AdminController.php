<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shipment;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Real Data
        $totalShipments = Shipment::count();
        $inTransit = Shipment::where('status', 'In Transit')->count();
        $outForDelivery = Shipment::where('status', 'Out for Delivery')->count();
        $deliveredToday = Shipment::where('status', 'Delivered')->whereDate('updated_at', today())->count();
        $pendingPickups = Shipment::where('status', 'Pending Pickup')->count();
        $ndrCount = Shipment::where('status', 'NDR')->count();
        $rtoCount = Shipment::whereIn('status', ['RTO Initiated', 'RTO Delivered'])->count();
        
        $totalRevenue = WalletTransaction::where('type', 'debit')->where('status', 'completed')->sum('amount');
        $totalSettlements = WalletTransaction::where('type', 'cod_remittance')->where('status', 'completed')->sum('amount');

        // Couriers performance
        $couriers = DB::table('shipments')
            ->select('courier_partner', DB::raw('count(*) as total'), DB::raw('sum(case when status = "Delivered" then 1 else 0 end) as delivered'), DB::raw('sum(case when status like "RTO%" then 1 else 0 end) as rto_count'))
            ->whereNotNull('courier_partner')
            ->groupBy('courier_partner')
            ->get();
            
        $courierPerformance = [];
        foreach ($couriers as $c) {
            $efficiency = $c->total > 0 ? round(($c->delivered / $c->total) * 100) : 0;
            $courierPerformance[] = [
                'name' => $c->courier_partner,
                'load' => $c->total,
                'efficiency' => $efficiency,
                'rto' => $c->rto_count
            ];
        }

        // Weak zones based on NDR (calculate percentage)
        $weakZones = DB::table('shipments')
            ->select('delivery_pincode as pincode', 'delivery_city as city', DB::raw('count(*) as total_orders'), DB::raw('sum(case when status = "NDR" then 1 else 0 end) as total_ndr'))
            ->groupBy('delivery_pincode', 'delivery_city')
            ->having('total_ndr', '>', 0)
            ->orderByDesc('total_ndr')
            ->limit(4)
            ->get()->map(function ($z) {
                $rate = $z->total_orders > 0 ? round(($z->total_ndr / $z->total_orders) * 100) : 0;
                return ['pincode' => $z->pincode, 'city' => $z->city, 'ndr_rate' => $rate, 'total_ndr' => $z->total_ndr];
            });

        // System Overview Data
        $activeSellers = User::where('role', 'seller')->count();
        $activeHubs = User::where('role', 'franchise')->count();
        $totalRiders = User::whereIn('role', ['rider', 'pickup_rider', 'delivery_rider'])->count();

        // Recent Bookings
        $recentShipmentsRaw = Shipment::with('user')->latest()->limit(5)->get();
        $recentShipments = $recentShipmentsRaw->map(function ($s) {
            return [
                'awb' => $s->awb_number,
                'seller' => $s->user->name ?? 'Unknown',
                'status' => $s->status
            ];
        });

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
            'activeSellers',
            'activeHubs',
            'totalRiders',
            'recentShipments'
        ));
    }

    public function liveMap()
    {
        $activeRiders = User::whereIn('role', ['rider', 'pickup_rider', 'delivery_rider'])
                        ->whereNotNull('latitude')
                        ->whereNotNull('longitude')
                        ->get();
        return view('admin.map', compact('activeRiders'));
    }
}


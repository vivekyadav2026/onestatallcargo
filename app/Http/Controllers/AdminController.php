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

        // Couriers performance mock/dynamic blend
        $couriers = DB::table('shipments')
            ->select('courier_partner', DB::raw('count(*) as total'), DB::raw('sum(case when status = "Delivered" then 1 else 0 end) as delivered'))
            ->whereNotNull('courier_partner')
            ->groupBy('courier_partner')
            ->get();
            
        $courierPerformance = [];
        foreach ($couriers as $c) {
            $efficiency = $c->total > 0 ? round(($c->delivered / $c->total) * 100) : 0;
            $courierPerformance[] = [
                'name' => $c->courier_partner,
                'load' => $c->total,
                'efficiency' => $efficiency
            ];
        }

        // Weak zones based on NDR
        $weakZones = DB::table('shipments')
            ->select('delivery_pincode as pincode', 'delivery_city as city', DB::raw('count(*) as total_ndr'))
            ->where('status', 'NDR')
            ->groupBy('delivery_pincode', 'delivery_city')
            ->orderByDesc('total_ndr')
            ->limit(3)
            ->get()->map(function ($z) {
                return ['pincode' => $z->pincode, 'city' => $z->city, 'ndr_rate' => $z->total_ndr];
            });

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


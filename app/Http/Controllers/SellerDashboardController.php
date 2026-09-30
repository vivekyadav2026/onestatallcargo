<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Courier;
use App\Models\Banner;
use App\Models\WeightDiscrepancy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        // Base query for user's shipments
        $baseQuery = Shipment::where('user_id', $userId);

        // Filter by courier if selected
        if ($request->filled('courier') && $request->courier !== 'all') {
            $baseQuery->where('courier_partner', $request->courier);
        }

        // Filter by shipment channel / type if selected
        if ($request->filled('shipment_type') && $request->shipment_type !== 'all') {
            $baseQuery->where('shipment_type', $request->shipment_type);
        }

        // Filter by date range if provided
        if ($request->filled('days') && is_numeric($request->days)) {
            $baseQuery->where('created_at', '>=', Carbon::now()->subDays((int)$request->days));
        }

        // Aggregate counts by status
        $statusGroup = (clone $baseQuery)
            ->selectRaw("LOWER(TRIM(status)) as status_key, count(*) as total")
            ->groupBy('status_key')
            ->pluck('total', 'status_key')
            ->toArray();

        // 1. Operations Overview Metrics
        $totalOrders = (clone $baseQuery)->count();
        $todayShipments = (clone $baseQuery)->whereDate('created_at', Carbon::today())->count();
        $yesterdayShipments = (clone $baseQuery)->whereDate('created_at', Carbon::yesterday())->count();
        
        $unshippedCount = ($statusGroup['new'] ?? 0) 
            + ($statusGroup['manifested'] ?? 0) 
            + ($statusGroup['booked'] ?? 0)
            + ($statusGroup['pending'] ?? 0)
            + ($statusGroup['new order'] ?? 0);

        $totalLoadKg = round((float)(clone $baseQuery)->sum('weight_kg'), 2);
        
        $totalShippingCharge = (float)(clone $baseQuery)->sum('shipping_charge');
        $avgShippingCost = $totalOrders > 0 ? round($totalShippingCharge / $totalOrders, 2) : 0.00;

        // 2. Journey Stages
        $pickupsScheduled = ($statusGroup['pickup_scheduled'] ?? 0) 
            + ($statusGroup['pickups'] ?? 0)
            + ($statusGroup['pickup scheduled'] ?? 0);

        $inTransit = ($statusGroup['in_transit'] ?? 0) 
            + ($statusGroup['transit'] ?? 0)
            + ($statusGroup['in transit'] ?? 0);

        $outForDelivery = ($statusGroup['out_for_delivery'] ?? 0) 
            + ($statusGroup['ofd'] ?? 0)
            + ($statusGroup['out for delivery'] ?? 0);

        $deliveredOrders = ($statusGroup['delivered'] ?? 0);

        // 3. Exceptions
        $ndrCount = ($statusGroup['ndr'] ?? 0) 
            + ($statusGroup['action_required'] ?? 0) 
            + ($statusGroup['undelivered'] ?? 0);

        $rtoInTransit = ($statusGroup['rto_transit'] ?? 0) 
            + ($statusGroup['rto in-transit'] ?? 0) 
            + ($statusGroup['rto_in_transit'] ?? 0)
            + ($statusGroup['rto in transit'] ?? 0);

        $rtoDelivered = ($statusGroup['rto'] ?? 0) 
            + ($statusGroup['rto delivered'] ?? 0) 
            + ($statusGroup['rto_delivered'] ?? 0);

        $lostCount = ($statusGroup['lost'] ?? 0) 
            + ($statusGroup['damaged'] ?? 0);

        // Exception Total
        $totalExceptions = $ndrCount + $rtoInTransit + $rtoDelivered + $lostCount;

        // 4. Weight discrepancies
        $weightDiscrepancies = WeightDiscrepancy::where('user_id', $userId)->whereIn('status', ['pending', 'disputed'])->count();

        // 5. COD of delivered items pending remittance
        $codPending = (clone $baseQuery)
            ->where('is_cod', true)
            ->whereIn('status', ['Delivered', 'delivered'])
            ->where(function($q) {
                $q->whereNull('cod_remitted')->orWhere('cod_remitted', false);
            })
            ->sum('invoice_value');

        // 6. Recent Shipments
        $recentShipments = (clone $baseQuery)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Active Courier Partners for the filter dropdown
        $couriers = Courier::where('is_active', true)->get();

        $kyc = $user->kyc;
        $banner = Banner::where('is_active', true)->first();

        return view('seller.dashboard', compact(
            'user', 
            'totalOrders', 
            'todayShipments',
            'yesterdayShipments',
            'unshippedCount',
            'totalLoadKg',
            'avgShippingCost',
            'pickupsScheduled', 
            'inTransit',
            'outForDelivery',
            'deliveredOrders', 
            'ndrCount',
            'rtoInTransit',
            'rtoDelivered',
            'lostCount',
            'totalExceptions',
            'weightDiscrepancies', 
            'codPending', 
            'recentShipments', 
            'couriers',
            'kyc',
            'banner'
        ));
    }
}

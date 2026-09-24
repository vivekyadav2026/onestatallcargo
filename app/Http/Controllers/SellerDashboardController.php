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
        $deliveredOrders = Shipment::where('user_id', $user->id)->where('status', 'Delivered')->count();
        
        // COD of delivered items not yet remitted
        $codPending = Shipment::where('user_id', $user->id)
            ->where('is_cod', true)
            ->where('status', 'Delivered')
            ->sum('invoice_value');

        $recentShipments = Shipment::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('seller.dashboard', compact(
            'user', 'totalOrders', 'deliveredOrders', 'codPending', 'recentShipments'
        ));
    }
}

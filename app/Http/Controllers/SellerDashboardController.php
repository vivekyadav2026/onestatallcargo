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
        
        $todayOrders = Shipment::where('user_id', $user->id)->whereDate('created_at', today())->count();
        $pendingPickups = Shipment::where('user_id', $user->id)->where('status', 'Manifested')->count();
        $activeNDR = Shipment::where('user_id', $user->id)->where('status', 'NDR')->count();
        
        // COD of delivered items not yet remitted
        $pendingCOD = Shipment::where('user_id', $user->id)
            ->where('is_cod', true)
            ->where('status', 'Delivered')
            ->sum('invoice_value');

        $recentShipments = Shipment::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('seller.dashboard', compact(
            'todayOrders', 'pendingPickups', 'activeNDR', 'pendingCOD', 'recentShipments'
        ));
    }
}

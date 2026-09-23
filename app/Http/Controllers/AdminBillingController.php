<?php
namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBillingController extends Controller
{
    public function index()
    {
        // Calculate COD pending remittance grouped by Seller
        $ledgers = Shipment::where('is_cod', true)
            ->where('status', 'Delivered')
            ->where('cod_remitted', false)
            ->select('user_id', DB::raw('SUM(invoice_value) as total_cod'), DB::raw('COUNT(id) as total_shipments'))
            ->groupBy('user_id')
            ->with('user')
            ->get();
            
        return view('admin.billing.index', compact('ledgers'));
    }

    public function remit(Request $request, $userId)
    {
        Shipment::where('user_id', $userId)
            ->where('is_cod', true)
            ->where('status', 'Delivered')
            ->where('cod_remitted', false)
            ->update(['cod_remitted' => true]);

        return back()->with('success', 'COD Remittance settled successfully for seller.');
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBillingController extends Controller {

    public function index()
    {
        // Calculate COD pending remittance grouped by Seller
        $ledgers = Shipment::where('is_cod', true)
            ->where('status', 'Delivered')
            ->where('cod_remitted', false)
            ->select('user_id', DB::raw('SUM(invoice_value) as total_cod'), DB::raw('COUNT(id) as total_shipments'))
            ->groupBy('user_id')
            ->with('user')
            ->paginate(15)->withQueryString();
            
        $totalPendingCOD = Shipment::where('is_cod', true)->where('status', 'Delivered')->where('cod_remitted', false)->sum('invoice_value');
        $totalSettledCOD = Shipment::where('is_cod', true)->where('status', 'Delivered')->where('cod_remitted', true)->sum('invoice_value');

        return view('admin.billing.index', compact('ledgers', 'totalPendingCOD', 'totalSettledCOD'));
    }

    public function remit(Request $request, $userId)
    {
        DB::transaction(function () use ($userId) {
            $user = User::findOrFail($userId);

            // Get total pending COD
            $totalCod = Shipment::where('user_id', $userId)
                ->where('is_cod', true)
                ->where('status', 'Delivered')
                ->where('cod_remitted', false)
                ->sum('invoice_value');

            // If wallet is negative, clear it from the COD payout (up to the total COD amount)
            if ($user->wallet_balance < 0) {
                $due = abs($user->wallet_balance);
                
                if ($totalCod >= $due) {
                    // Settle full due
                    $user->wallet_balance = 0;
                } else {
                    // Settle partial due
                    $user->wallet_balance += $totalCod;
                }
                $user->save();
            }

            // Mark COD as remitted
            Shipment::where('user_id', $userId)
                ->where('is_cod', true)
                ->where('status', 'Delivered')
                ->where('cod_remitted', false)
                ->update(['cod_remitted' => true]);
        });

        return back()->with('success', 'COD Remittance settled successfully, and any pending wallet dues were adjusted automatically.');
    }

    public function remitEarlyCod(Request $request, $userId)
    {
        DB::transaction(function () use ($userId) {
            $user = User::findOrFail($userId);

            // Get all pending COD shipments
            $shipments = Shipment::where('user_id', $userId)
                ->where('is_cod', true)
                ->where('status', 'Delivered')
                ->where('cod_remitted', false)
                ->get();

            if ($shipments->isEmpty()) return;

            $totalCod = $shipments->sum('invoice_value');
            
            // Calculate fee
            $feePercent = $user->early_cod_fee ?? 0;
            $feeAmount = ($totalCod * $feePercent) / 100;
            $netPayout = $totalCod - $feeAmount;

            // Credit the seller's wallet
            $user->wallet_balance += $netPayout;
            $user->save();

            // Record transaction
            \App\Models\WalletTransaction::create([
                'user_id' => $userId,
                'type' => 'credit',
                'amount' => $netPayout,
                'balance_after' => $user->wallet_balance,
                'reference_id' => 'EARLY_COD_' . time(),
                'description' => 'Early COD Payout (' . $shipments->count() . ' parcels). Fee deducted: ' . $feePercent . '%'
            ]);

            // Mark COD as remitted and record fee
            foreach($shipments as $shipment) {
                $shipment->cod_remittance_status = 'processed';
                $shipment->early_cod_fee_deducted = ($shipment->invoice_value * $feePercent) / 100;
                $shipment->cod_remitted = true;
                $shipment->save();
            }
        });

        return back()->with('success', 'Early COD successfully processed and credited to seller wallet!');
    }
}

<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Franchise;
use App\Models\Hub;
use App\Models\Shipment;
use App\Models\WalletTransaction;

class HubWalletController extends Controller
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
            'franchise_id' => $franchise ? $franchise->id : null,
            'franchise' => $franchise
        ];
    }

    public function index()
    {
        $scope = $this->getAuthScope();
        $user = $scope['user'];
        $franchise = $scope['franchise'];
        
        $transactions = [];
        if (class_exists(WalletTransaction::class)) {
            $transactions = WalletTransaction::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15)->withQueryString();
        }

        // Calculate Pending COD Remittance
        $pendingCod = 0;
        $totalEarnings = 0;
        $pendingEarnings = 0;
        
        if ($franchise) {
            $pincodes = is_array($franchise->serviceable_pincodes) ? $franchise->serviceable_pincodes : (json_decode($franchise->serviceable_pincodes, true) ?? []);
            if (!is_array($pincodes)) { $pincodes = $pincodes ? [$pincodes] : []; }

            $deliveredQuery = Shipment::where('status', 'Delivered')
                ->where(function($q) use ($franchise, $pincodes) {
                    $q->where('franchise_id', $franchise->id);
                    if (!empty($pincodes)) {
                        $q->orWhereIn('delivery_pincode', $pincodes);
                    }
                });

            // Pending COD to be remitted to admin
            $pendingCod = (clone $deliveredQuery)
                ->where(function($q) {
                    $q->where('payment_type', 'COD')->orWhere('is_cod', 1);
                })
                ->where(function($q) {
                    $q->where('cod_remitted', 0)->orWhereNull('cod_remitted');
                })
                ->sum(\Illuminate\Support\Facades\DB::raw('COALESCE(cod_amount, invoice_value, 0)'));
                
            // Ledger-backed Franchise earnings from FranchiseCommission
            $totalEarnings = \App\Models\FranchiseCommission::where('franchise_id', $franchise->id)
                ->where('status', 'credited')
                ->sum('amount');
            $pendingEarnings = \App\Models\FranchiseCommission::where('franchise_id', $franchise->id)
                ->where('status', 'pending')
                ->sum('amount');
        }

        return view('hub.wallet.index', compact('user', 'transactions', 'pendingCod', 'totalEarnings', 'pendingEarnings'));
    }

    public function remitCod(Request $request)
    {
        $scope = $this->getAuthScope();
        $user = $scope['user'];
        $franchise = $scope['franchise'];

        if (!$franchise) {
            return back()->with('error', 'Only Franchises can remit COD.');
        }

        $pincodes = is_array($franchise->serviceable_pincodes) ? $franchise->serviceable_pincodes : (json_decode($franchise->serviceable_pincodes, true) ?? []);
        if (!is_array($pincodes)) { $pincodes = $pincodes ? [$pincodes] : []; }

        $deliveredQuery = Shipment::where('status', 'Delivered')
            ->where(function($q) use ($franchise, $pincodes) {
                $q->where('franchise_id', $franchise->id);
                if (!empty($pincodes)) {
                    $q->orWhereIn('delivery_pincode', $pincodes);
                }
            })
            ->where(function($q) {
                $q->where('payment_type', 'COD')->orWhere('is_cod', 1);
            })
            ->where(function($q) {
                $q->where('cod_remitted', 0)->orWhereNull('cod_remitted');
            });

        $pendingCodAmount = (clone $deliveredQuery)->sum(\Illuminate\Support\Facades\DB::raw('COALESCE(cod_amount, invoice_value, 0)'));

        if ($pendingCodAmount <= 0) {
            return back()->with('error', 'No pending COD amount to remit.');
        }

        // Optional: Check if wallet balance is enough to auto-deduct, or just mark it as remitted
        // In a real system, this would redirect to a payment gateway (Cashfree/Razorpay).
        // For now, we simulate success and mark them as remitted.

        \Illuminate\Support\Facades\DB::transaction(function() use ($deliveredQuery, $pendingCodAmount, $user) {
            $deliveredQuery->update([
                'cod_remitted' => 1,
                'cod_remittance_date' => now()
            ]);

            if (class_exists(WalletTransaction::class)) {
                WalletTransaction::create([
                    'user_id' => $user->id,
                    'reference_id' => 'REMIT_' . strtoupper(uniqid()),
                    'description' => 'COD Remittance to Admin',
                    'type' => 'debit',
                    'amount' => $pendingCodAmount,
                    'balance_after' => $user->wallet_balance ?? 0, // Mock balance update if needed
                ]);
            }
        });

        return back()->with('success', 'COD Remittance of ₹' . number_format($pendingCodAmount, 2) . ' successful.');
    }
}
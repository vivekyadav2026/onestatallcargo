<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeightDiscrepancy;
use Illuminate\Support\Facades\Auth;

class SellerWeightController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'action_required');
        
        $query = WeightDiscrepancy::with('shipment')->where('user_id', Auth::id());
        
        // Stats
        $totalDiscrepancies = WeightDiscrepancy::where('user_id', Auth::id())->count();
        $actionRequired = WeightDiscrepancy::where('user_id', Auth::id())->where('status', 'pending')->count();
        $amountOnHold = WeightDiscrepancy::where('user_id', Auth::id())->whereIn('status', ['pending', 'disputed'])->sum('discrepancy_fee');
        $resolved = WeightDiscrepancy::where('user_id', Auth::id())->where('status', 'seller_won')->count();

        // Tab logic
        if ($tab === 'action_required') {
            $query->where('status', 'pending');
        } elseif ($tab === 'disputed') {
            $query->where('status', 'disputed');
        } elseif ($tab === 'accepted') {
            $query->where('status', 'accepted');
        } elseif ($tab === 'closed') {
            $query->whereIn('status', ['seller_won', 'courier_won']);
        } elseif ($tab === 'all') {
            // no filter
        }
        
        $discrepancies = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('seller.weight.index', compact(
            'discrepancies', 
            'tab', 
            'totalDiscrepancies', 
            'actionRequired', 
            'amountOnHold', 
            'resolved'
        ));
    }

    public function action(Request $request, $id)
    {
        $validated = $request->validate([
            'action' => 'required|in:accept,dispute',
            'evidence_image' => 'nullable|image|max:5120',
            'remarks' => 'nullable|string'
        ]);

        $discrepancy = WeightDiscrepancy::where('user_id', Auth::id())->findOrFail($id);

        if ($validated['action'] === 'accept') {
            $discrepancy->status = 'accepted';
            try {
                app(\App\Services\WalletService::class)->deduct(
                    Auth::id(),
                    $discrepancy->discrepancy_fee,
                    'Accepted weight discrepancy charge for AWB ' . ($discrepancy->shipment->awb_number ?? 'N/A'),
                    $discrepancy->shipment->awb_number ?? (string)$discrepancy->shipment_id
                );
            } catch (\Exception $e) {
                // Deduct into negative balance if wallet is empty
                $user = Auth::user();
                $user->wallet_balance -= $discrepancy->discrepancy_fee;
                $user->save();

                \App\Models\WalletTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'debit',
                    'amount' => $discrepancy->discrepancy_fee,
                    'description' => 'Accepted weight discrepancy charge for AWB ' . ($discrepancy->shipment->awb_number ?? 'N/A'),
                    'reference_id' => $discrepancy->shipment->awb_number ?? (string)$discrepancy->shipment_id,
                    'status' => 'success',
                    'balance_after' => $user->wallet_balance
                ]);
            }
        } else {
            $discrepancy->status = 'disputed';
            if ($request->hasFile('evidence_image')) {
                $discrepancy->evidence_image = $request->file('evidence_image')->store('evidence', 'public');
            }
        }

        $discrepancy->save();

        return back()->with('success', 'Discrepancy action recorded successfully.');
    }
}


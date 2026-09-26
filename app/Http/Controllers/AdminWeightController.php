<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeightDiscrepancy;
use App\Models\WalletTransaction;

class AdminWeightController extends Controller
{
    public function index(Request $request)
    {
        $discrepancies = WeightDiscrepancy::with(['shipment', 'user'])->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.weight.index', compact('discrepancies'));
    }

    public function action(Request $request, $id)
    {
        $validated = $request->validate([
            'action' => 'required|in:seller_won,courier_won'
        ]);

        $discrepancy = WeightDiscrepancy::findOrFail($id);
        
        $discrepancy->status = $validated['action'];
        $discrepancy->save();

        if ($validated['action'] === 'courier_won') {
            // Deduct from seller wallet
            WalletTransaction::create([
                'user_id' => $discrepancy->user_id,
                'type' => 'debit',
                'amount' => $discrepancy->discrepancy_fee,
                'description' => 'Weight discrepancy charge for AWB ' . ($discrepancy->shipment->awb_number ?? 'N/A'),
                'reference_id' => $discrepancy->shipment_id,
                'status' => 'completed'
            ]);
        }

        return back()->with('success', 'Discrepancy resolved successfully.');
    }
}


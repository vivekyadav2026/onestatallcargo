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

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $header = array_shift($data);

        $count = 0;
        foreach ($data as $row) {
            if (count($row) < 3) continue;
            
            // Expected CSV Format: AWB, Charged_Weight, Extra_Fee
            $awb = trim($row[0]);
            $chargedWeight = (float) trim($row[1]);
            $fee = (float) trim($row[2]);

            $shipment = \App\Models\Shipment::where('awb_number', $awb)->first();

            if ($shipment && $chargedWeight > $shipment->weight_kg) {
                WeightDiscrepancy::updateOrCreate(
                    ['shipment_id' => $shipment->id],
                    [
                        'user_id' => $shipment->user_id,
                        'applied_weight' => $shipment->weight_kg,
                        'charged_weight' => $chargedWeight,
                        'discrepancy_fee' => $fee,
                        'status' => 'pending',
                        'expires_at' => now()->addDays(3) // 72 hours for seller to dispute
                    ]
                );
                $count++;
            }
        }

        return back()->with('success', "Processed $count discrepancies from courier billing file.");
    }
}


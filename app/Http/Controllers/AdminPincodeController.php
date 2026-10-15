<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceablePincode;
use App\Models\Franchise;
use App\Models\Hub;

class AdminPincodeController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceablePincode::with('franchise.user');

        if ($request->filled('search')) {
            $query->where('pincode', 'like', "%{$request->search}%")
                  ->orWhere('city', 'like', "%{$request->search}%");
        }

        $pincodes = $query->orderBy('created_at', 'desc')->paginate(50)->withQueryString();
        $franchises = Franchise::with('user')->where('status', 'approved')->get();

        return view('admin.pincodes.index', compact('pincodes', 'franchises'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'franchise_id' => 'required|exists:franchises,id',
            'pincodes' => 'required|string', // Comma separated
            'city' => 'nullable|string',
            'state' => 'nullable|string'
        ]);

        $pincodesArray = array_map('trim', explode(',', $request->pincodes));
        $count = 0;

        foreach ($pincodesArray as $pin) {
            if (!empty($pin)) {
                ServiceablePincode::updateOrCreate(
                    ['pincode' => $pin],
                    [
                        'franchise_id' => $request->franchise_id,
                        'city' => $request->city ?? 'Unknown',
                        'state' => $request->state ?? 'Unknown',
                        'is_active' => true
                    ]
                );
                $count++;
            }
        }

        $franchise = Franchise::find($request->franchise_id);
        if ($franchise) {
            $existingPins = is_array($franchise->serviceable_pincodes) 
                ? $franchise->serviceable_pincodes 
                : (json_decode($franchise->serviceable_pincodes, true) ?? []);
            if (!is_array($existingPins)) {
                $existingPins = is_string($franchise->serviceable_pincodes) ? array_map('trim', explode(',', $franchise->serviceable_pincodes)) : [];
            }
            $cleanPins = array_values(array_filter(array_map('trim', $pincodesArray)));
            $merged = array_values(array_unique(array_filter(array_merge($existingPins, $cleanPins))));
            $franchise->serviceable_pincodes = $merged;
            $franchise->save();
        }

        return back()->with('success', "{$count} Pincode(s) successfully mapped to Franchise.");
    }

    public function import(Request $request)
    {
        $request->validate([
            'franchise_id' => 'required|exists:franchises,id',
            'import_file' => 'required|file|mimes:csv,txt'
        ]);

        $path = $request->file('import_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $header = array_shift($data); // Assume Header: Pincode, City, State

        $count = 0;
        $importedPins = [];
        foreach ($data as $row) {
            if (empty($row[0])) continue;

            $pin = trim($row[0]);
            $city = trim($row[1] ?? 'Unknown');
            $state = trim($row[2] ?? 'Unknown');

            ServiceablePincode::updateOrCreate(
                ['pincode' => $pin],
                [
                    'franchise_id' => $request->franchise_id,
                    'city' => $city,
                    'state' => $state,
                    'is_active' => true
                ]
            );
            $importedPins[] = $pin;
            $count++;
        }

        $franchise = Franchise::find($request->franchise_id);
        if ($franchise && !empty($importedPins)) {
            $existingPins = is_array($franchise->serviceable_pincodes) 
                ? $franchise->serviceable_pincodes 
                : (json_decode($franchise->serviceable_pincodes, true) ?? []);
            if (!is_array($existingPins)) {
                $existingPins = is_string($franchise->serviceable_pincodes) ? array_map('trim', explode(',', $franchise->serviceable_pincodes)) : [];
            }
            $merged = array_values(array_unique(array_filter(array_merge($existingPins, $importedPins))));
            $franchise->serviceable_pincodes = $merged;
            $franchise->save();
        }

        return back()->with('success', "Bulk uploaded and mapped {$count} pincodes successfully.");
    }

    public function destroy($id)
    {
        $pincode = ServiceablePincode::findOrFail($id);
        if ($pincode->franchise_id) {
            $franchise = Franchise::find($pincode->franchise_id);
            if ($franchise) {
                $existingPins = is_array($franchise->serviceable_pincodes) 
                    ? $franchise->serviceable_pincodes 
                    : (json_decode($franchise->serviceable_pincodes, true) ?? []);
                if (!is_array($existingPins)) {
                    $existingPins = is_string($franchise->serviceable_pincodes) ? array_map('trim', explode(',', $franchise->serviceable_pincodes)) : [];
                }
                $updated = array_values(array_diff($existingPins, [$pincode->pincode]));
                $franchise->serviceable_pincodes = $updated;
                $franchise->save();
            }
        }
        $pincode->delete();
        return back()->with('success', 'Pincode mapping removed.');
    }
}

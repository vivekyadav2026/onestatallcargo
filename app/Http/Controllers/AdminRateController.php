<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use Illuminate\Http\Request;

class AdminRateController extends Controller
{
    public function index()
    {
        $rates = Rate::orderBy('zone_type')->get();
        return view('admin.rates.index', compact('rates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'zone_type' => 'required|string|max:50',
            'base_rate' => 'required|numeric|min:0',
            'additional_weight_rate' => 'required|numeric|min:0',
            'rto_surcharge' => 'required|numeric|min:0',
            'cod_surcharge' => 'required|numeric|min:0'
        ]);

        Rate::create([
            'zone_type' => strtoupper($validated['zone_type']),
            'base_rate' => $validated['base_rate'],
            'additional_weight_rate' => $validated['additional_weight_rate'],
            'rto_surcharge' => $validated['rto_surcharge'],
            'cod_surcharge' => $validated['cod_surcharge'],
        ]);

        return back()->with('success', 'New rate rule added successfully.');
    }

    public function update(Request $request, $id)
    {
        $rate = Rate::findOrFail($id);

        $validated = $request->validate([
            'base_rate' => 'required|numeric|min:0',
            'additional_weight_rate' => 'required|numeric|min:0',
            'rto_surcharge' => 'required|numeric|min:0',
            'cod_surcharge' => 'required|numeric|min:0'
        ]);

        $rate->update($validated);

        return back()->with('success', 'Rate rule updated successfully.');
    }
}

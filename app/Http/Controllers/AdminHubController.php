<?php

namespace App\Http\Controllers;

use App\Models\Hub;
use App\Models\User;
use Illuminate\Http\Request;

class AdminHubController extends Controller
{
    public function index()
    {
        $hubs = Hub::with('manager')->orderBy('created_at', 'desc')->paginate(15);
        $managers = User::where('role', 'franchise')->get();
        return view('admin.hubs.index', compact('hubs', 'managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hub_code' => 'required|string|unique:hubs,hub_code|max:20',
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'pincode' => 'required|string|max:20',
            'capacity' => 'required|numeric|min:1',
            'manager_id' => 'nullable|exists:users,id'
        ]);

        Hub::create([
            'hub_code' => strtoupper($validated['hub_code']),
            'name' => $validated['name'],
            'city' => $validated['city'],
            'pincode' => $validated['pincode'],
            'capacity' => $validated['capacity'],
            'manager_id' => $validated['manager_id'],
            'is_active' => true
        ]);

        return back()->with('success', 'Hub / Franchise created successfully.');
    }

    public function toggle($id)
    {
        $hub = Hub::findOrFail($id);
        $hub->is_active = !$hub->is_active;
        $hub->save();

        return back()->with('success', 'Hub status updated.');
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\Courier;
use Illuminate\Http\Request;

class AdminCourierController extends Controller
{
    public function index()
    {
        $couriers = Courier::orderBy('created_at', 'desc')->get();
        return view('admin.couriers.index', compact('couriers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'api_code' => 'required|string|unique:couriers,api_code|max:50'
        ]);

        Courier::create([
            'name' => $validated['name'],
            'api_code' => strtoupper($validated['api_code']),
            'is_active' => true
        ]);

        return back()->with('success', 'Courier partner added successfully.');
    }

    public function toggle($id)
    {
        $courier = Courier::findOrFail($id);
        $courier->is_active = !$courier->is_active;
        $courier->save();

        return back()->with('success', 'Courier status updated.');
    }
}

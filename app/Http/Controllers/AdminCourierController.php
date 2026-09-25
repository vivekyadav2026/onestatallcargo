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
            'mode' => 'required|in:sandbox,production',
            'credentials' => 'required|array',
            'credentials.api_url' => 'required|url',
            'credentials.api_key' => 'required|string',
        ]);

        Courier::create([
            'name' => $validated['name'],
            'mode' => $validated['mode'],
            'api_credentials' => $validated['credentials'], // This will be auto-encrypted due to model casting
            'is_active' => true
        ]);

        return back()->with('success', 'Courier partner added securely.');
    }

    public function toggle($id)
    {
        $courier = Courier::findOrFail($id);
        $courier->is_active = !$courier->is_active;
        $courier->save();

        return back()->with('success', 'Courier status updated.');
    }
}

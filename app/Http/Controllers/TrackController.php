<?php
namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    public function index()
    {
        return view('track');
    }

    public function track(Request $request)
    {
        $request->validate(['awb_number' => 'required|string']);
        
        $shipment = Shipment::where('awb_number', strtoupper($request->awb_number))->first();

        if (!$shipment) {
            return back()->with('error', 'No shipment found with that AWB Number.');
        }

        return view('track', compact('shipment'));
    }
}

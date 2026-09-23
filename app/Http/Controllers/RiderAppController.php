<?php
namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiderAppController extends Controller
{
    public function index()
    {
        // Mock rider assigned shipments
        // In real app, we filter by rider_id = Auth::id() and status
        $pendingPickups = Shipment::where('status', 'Manifested')->orderBy('created_at', 'desc')->get();
        $pendingDeliveries = Shipment::where('status', 'Out for Delivery')->orderBy('created_at', 'desc')->get();
        
        return view('rider.dashboard', compact('pendingPickups', 'pendingDeliveries'));
    }

    public function uploadEvidence(Request $request)
    {
        $validated = $request->validate([
            'awb_number' => 'required|string',
            'action_type' => 'required|string', // Pickup or Delivery
            'video_file' => 'nullable|file|mimes:mp4,mov,avi|max:20480',
            'otp' => 'nullable|string'
        ]);

        $shipment = Shipment::where('awb_number', $validated['awb_number'])->first();

        if (!$shipment) {
            return back()->with('error', 'Invalid AWB');
        }

        // Mock upload
        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('evidence', 'public');
            $shipment->video_evidence_url = '/storage/' . $path;
        } else {
            // Give it a dummy video URL for testing if none provided but button clicked
            $shipment->video_evidence_url = '/storage/evidence/dummy_video_proof.mp4';
        }

        if ($validated['action_type'] == 'Pickup') {
            $shipment->status = 'In Transit';
        } else {
            $shipment->status = 'Delivered';
        }

        $shipment->save();

        return back()->with('success', $validated['action_type'] . ' complete! Evidence uploaded for ' . $validated['awb_number']);
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RiderAppController extends Controller
{
    public function index()
    {
        $riderId = Auth::id();
        
        $pendingPickups = Shipment::where('status', 'Manifested')
                            ->where('rider_id', $riderId)
                            ->orderBy('created_at', 'desc')
                            ->get();
                            
        $pendingDeliveries = Shipment::where('status', 'Out for Delivery')
                            ->where('rider_id', $riderId)
                            ->orderBy('created_at', 'desc')
                            ->get();
        
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

        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('evidence', 'public');
            $shipment->video_evidence_url = '/storage/' . $path;
        } else {
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

    public function scan()
    {
        return view('rider.scan');
    }

    public function cod()
    {
        $riderId = Auth::id();
        $codShipments = Shipment::where('rider_id', $riderId)
                            ->where('status', 'Delivered')
                            ->where('is_cod', true)
                            ->orderBy('updated_at', 'desc')
                            ->get();
                            
        $totalCollected = $codShipments->sum('invoice_value');
        
        return view('rider.cod', compact('codShipments', 'totalCollected'));
    }

    public function profile()
    {
        $user = Auth::user();
        $totalDeliveries = Shipment::where('rider_id', $user->id)->where('status', 'Delivered')->count();
        return view('rider.profile', compact('user', 'totalDeliveries'));
    }

    public function history()
    {
        $riderId = Auth::id();
        $history = Shipment::where('rider_id', $riderId)
                    ->whereIn('status', ['Delivered', 'In Transit', 'NDR', 'RTO Initiated', 'RTO Delivered'])
                    ->orderBy('updated_at', 'desc')
                    ->paginate(20);
        return view('rider.history', compact('history'));
    }

    public function settings()
    {
        $user = Auth::user();
        return view('rider.settings', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'password' => 'nullable|string|min:6'
        ]);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return back()->with('success', 'Profile and Settings updated successfully!');
    }
}

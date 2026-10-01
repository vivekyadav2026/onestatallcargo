<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\ShipmentEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RiderAppController extends Controller
{
    public function index()
    {
        $riderId = Auth::id();

        $pendingPickups = Shipment::whereIn('status', ['Manifested', 'Pickup Scheduled'])
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
        return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
        $validated = $request->validate([
            'awb_number'  => 'required|string',
            'action_type' => 'required|string',
            'video_file'  => 'nullable|file|mimes:mp4,mov,avi|max:20480',
            'otp'         => 'nullable|string',
        ]);

        
        $shipment = Shipment::where('awb_number', $validated['awb_number'])
            ->where('rider_id', Auth::id())
            ->lockForUpdate()
            ->first();


        if (!$shipment) {
            return back()->with('error', 'Invalid AWB');
        }

        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('evidence', 'public');
            $shipment->video_evidence_url = '/storage/' . $path;
        }

        if ($validated['action_type'] == 'Pickup') {
            $shipment->status = 'In Transit';
        } else {
            $shipment->status = 'Delivered';
            app(\App\Services\NotificationService::class)->notifyShipmentUpdate($shipment, 'Good news! Your shipment ' . $shipment->awb_number . ' has been successfully delivered.');
        }

        $shipment->save();

        ShipmentEvent::create([
            'shipment_id' => $shipment->id,
            'status'      => $shipment->status,
            'location'    => $shipment->delivery_city ?? '',
            'remarks'     => $validated['action_type'] . ' evidence uploaded by rider',
        ]);

        return back()->with('success', $validated['action_type'] . ' complete! Evidence uploaded for ' . $validated['awb_number']);
        });
    }

    public function autoNdr(Request $request, $awb)
    {
        $validated = $request->validate([
            'ndr_reason' => 'required|string',
        ]);

        $shipment = Shipment::where('awb_number', $awb)
                            ->where('rider_id', Auth::id())
                            ->firstOrFail();

        $shipment->status     = 'NDR';
        $shipment->ndr_reason = $validated['ndr_reason'];
        $shipment->save();

        ShipmentEvent::create([
            'shipment_id' => $shipment->id,
            'status'      => 'NDR',
            'remarks'     => 'Rider marked as NDR: ' . $validated['ndr_reason'],
            'location'    => $shipment->delivery_city ?? '',
        ]);

        return back()->with('success', 'Shipment marked as NDR successfully.');
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
        $totalDeliveries = Shipment::where('rider_id', $user->id)
                                   ->where('status', 'Delivered')
                                   ->count();
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
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|max:15',
            'password' => 'nullable|string|min:6',
            'avatar'   => 'nullable|image|max:2048',
        ]);

        $user->name  = $validated['name'];
        $user->phone = $validated['phone'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        return back()->with('success', 'Profile and Settings updated successfully!');
    }
}


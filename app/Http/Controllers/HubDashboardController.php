<?php
namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HubDashboardController extends Controller
{
    private function checkAccess()
    {
        $user = Auth::user();
        
        // 1. Check Franchise Approval
        $franchise = \App\Models\Franchise::where('user_id', $user->id)->first();
        if ($franchise && $franchise->status !== 'approved') {
            return 'Your franchise application is pending Admin approval. You cannot operate the hub yet.';
        }

        // 2. Check KYC
        $kyc = \App\Models\Kyc::where('user_id', $user->id)->first();
        if (!$kyc || $kyc->status !== 'approved') {
            return 'Your KYC is pending or unverified. You cannot operate the hub yet.';
        }

        return null;
    }

    public function index()
    {
        $recentScans = Shipment::orderBy('updated_at', 'desc')->take(10)->get();
        $riders = \App\Models\User::where('role', 'rider')->get();
        $kyc = \App\Models\Kyc::where('user_id', Auth::id())->first();
        $franchise = \App\Models\Franchise::where('user_id', Auth::id())->first();
        
        return view('hub.dashboard', compact('recentScans', 'riders', 'kyc', 'franchise'));
    }

    public function scan(Request $request)
    {
        if ($error = $this->checkAccess()) return back()->with('error', $error);

        $validated = $request->validate([
            'awb_number' => 'required|string',
            'action' => 'required|string|in:Receive,Dispatch,Out for Delivery',
            'rider_id' => 'nullable|exists:users,id'
        ]);

        $shipment = Shipment::where('awb_number', $validated['awb_number'])->first();

        if (!$shipment) {
            return back()->with('error', 'Shipment not found for AWB: ' . $validated['awb_number']);
        }

        $shipment->status = $validated['action'];
        
        if (!empty($validated['rider_id'])) {
            $shipment->rider_id = $validated['rider_id'];
        }

        $shipment->save();

        \App\Models\ShipmentEvent::create([
            'shipment_id' => $shipment->id,
            'status' => $validated['action'],
            'location' => $shipment->delivery_city ?? 'Hub',
            'remarks' => 'Hub scanned package: ' . $validated['action']
        ]);

        return back()->with('success', 'Shipment ' . $validated['awb_number'] . ' marked as: ' . $validated['action']);
    }

    public function bagging()
    {
        if ($error = $this->checkAccess()) return back()->with('error', $error);
        return view('hub.bagging');
    }

    public function createBag(Request $request)
    {
        if ($error = $this->checkAccess()) return back()->with('error', $error);
        return back()->with('success', 'Bag created successfully.');
    }

    public function scanToBag(Request $request)
    {
        if ($error = $this->checkAccess()) return back()->with('error', $error);
        return back()->with('success', 'Scanned to bag successfully.');
    }

    public function profile()
    {
        $user = Auth::user();
        $franchise = \App\Models\Franchise::where('user_id', $user->id)->first();
        $hub = \App\Models\Hub::where('manager_id', $user->id)->first();
        
        return view('hub.profile', compact('user', 'franchise', 'hub'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:6',
        ]);

        $userData = [
            'name' => $validated['name'],
            'phone' => $validated['phone'],
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        $user->update($userData);

        return back()->with('success', 'Profile updated successfully.');
    }
}
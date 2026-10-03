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
        $kyc = \App\Models\Kyc::where('user_id', Auth::id())->first();
        $franchise = \App\Models\Franchise::where('user_id', Auth::id())->first();
        $hubs = \App\Models\Hub::where('manager_id', Auth::id())->get();
        
        $shipmentsQuery = Shipment::query();
        if ($franchise) {
            $pincodes = is_array($franchise->serviceable_pincodes) ? $franchise->serviceable_pincodes : (json_decode($franchise->serviceable_pincodes, true) ?? []);
            if (!is_array($pincodes)) {
                $pincodes = $pincodes ? [$pincodes] : [];
            }
            $shipmentsQuery->where(function($q) use ($franchise, $pincodes) {
                $q->where('franchise_id', $franchise->id)
                  ->orWhere('user_id', Auth::id());
                if (!empty($pincodes)) {
                    $q->orWhereIn('pickup_pincode', $pincodes)
                      ->orWhereIn('delivery_pincode', $pincodes);
                }
            });
        } else {
            $shipmentsQuery->where('user_id', Auth::id());
        }

        // --- DASHBOARD METRICS ---
        $totalBookings = (clone $shipmentsQuery)->count();
        $pendingPickup = (clone $shipmentsQuery)->where('status', 'Pending')->count();
        $outForDelivery = (clone $shipmentsQuery)->where('status', 'Out for Delivery')->count();
        $delivered = (clone $shipmentsQuery)->where('status', 'Delivered')->count();
        $ndr = (clone $shipmentsQuery)->where('status', 'NDR')->count();
        $rto = (clone $shipmentsQuery)->where('status', 'RTO')->count();
        
        // Mock COD Pending and Earnings for now, update with Wallet/COD logics later
        $codPending = (clone $shipmentsQuery)->where('payment_type', 'COD')->where('status', 'Delivered')->sum('invoice_value') ?? 0;
        $todayEarnings = (clone $shipmentsQuery)->whereDate('created_at', today())->count() * 45; // Mock 45rs per booking
        
        // Graph Data (Last 7 days)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $chartLabels[] = $date->format('M d');
            $chartData[] = (clone $shipmentsQuery)->whereDate('created_at', $date)->count() * 45; // Mock earnings
        }
        
        $recentScans = (clone $shipmentsQuery)->orderBy('updated_at', 'desc')->take(10)->get();

        $riders = \App\Models\User::where('role', 'rider')
            ->whereHas('rider', function($query) use ($franchise, $hubs) {
                if ($franchise) {
                    $query->where('franchise_id', $franchise->id);
                } else if ($hubs->count() > 0) {
                    $query->whereIn('hub_id', $hubs->pluck('id'));
                } else {
                    $query->where('id', 0); // empty
                }
            })->get();
        
        return view('hub.dashboard', compact(
            'recentScans', 'riders', 'kyc', 'franchise', 
            'totalBookings', 'pendingPickup', 'outForDelivery', 'delivered', 
            'ndr', 'rto', 'codPending', 'todayEarnings', 
            'chartLabels', 'chartData'
        ));
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

        $franchise = \App\Models\Franchise::where('user_id', Auth::id())->first();
        if ($franchise) {
            $pincodes = is_array($franchise->serviceable_pincodes) ? $franchise->serviceable_pincodes : (json_decode($franchise->serviceable_pincodes, true) ?? []);
            if (!is_array($pincodes)) {
                $pincodes = $pincodes ? [$pincodes] : [];
            }
            $hasPincodeMatch = in_array($shipment->pickup_pincode, $pincodes) || in_array($shipment->delivery_pincode, $pincodes);
            if ($shipment->franchise_id !== $franchise->id && $shipment->user_id !== Auth::id() && !$hasPincodeMatch) {
                return back()->with('error', 'UNAUTHORIZED: Shipment does not belong to your franchise area.');
            }
        } else {
            if ($shipment->user_id !== Auth::id()) {
                return back()->with('error', 'UNAUTHORIZED: Shipment does not belong to your account.');
            }
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
        
        $user = Auth::user();
        $franchise = \App\Models\Franchise::where('user_id', $user->id)->first();
        $hub = \App\Models\Hub::where('manager_id', $user->id)->first();

        $bags = \App\Models\Bag::query();
        if ($franchise) {
            $bags->where('franchise_id', $franchise->id);
        } else if ($hub) {
            $bags->where('hub_id', $hub->id);
        }

        $bags = $bags->orderBy('created_at', 'desc')->take(20)->get();

        return view('hub.bagging', compact('bags'));
    }

    public function createBag(Request $request)
    {
        if ($error = $this->checkAccess()) return back()->with('error', $error);
        
        $user = Auth::user();
        $franchise = \App\Models\Franchise::where('user_id', $user->id)->first();
        $hub = \App\Models\Hub::where('manager_id', $user->id)->first();

        $bag = new \App\Models\Bag();
        $bag->bag_number = 'BAG-' . strtoupper(Str::random(8));
        $bag->status = 'Open';
        
        if ($franchise) {
            $bag->franchise_id = $franchise->id;
        } else if ($hub) {
            $bag->hub_id = $hub->id;
        }
        $bag->save();

        return back()->with('success', 'Bag ' . $bag->bag_number . ' created successfully.');
    }

    public function scanToBag(Request $request)
    {
        if ($error = $this->checkAccess()) return back()->with('error', $error);

        $request->validate([
            'bag_id' => 'required|exists:bags,id',
            'awb_number' => 'required|string',
        ]);

        $bag = \App\Models\Bag::findOrFail($request->bag_id);
        if ($bag->status !== 'Open') {
            return back()->with('error', 'Bag is not open for adding parcels.');
        }

        $shipment = Shipment::where('awb_number', $request->awb_number)->first();
        if (!$shipment) {
            return back()->with('error', 'Shipment not found for AWB: ' . $request->awb_number);
        }

        if ($shipment->bag_id) {
            return back()->with('error', 'Shipment is already in a bag!');
        }

        $shipment->bag_id = $bag->id;
        $shipment->status = 'Bagged';
        $shipment->save();

        \App\Models\ShipmentEvent::create([
            'shipment_id' => $shipment->id,
            'status' => 'Bagged',
            'location' => 'Hub/Franchise',
            'remarks' => 'Scanned into Bag: ' . $bag->bag_number
        ]);

        return back()->with('success', 'Parcel ' . $request->awb_number . ' successfully scanned to bag ' . $bag->bag_number);
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
            'avatar' => 'nullable|image|max:2048',
        ]);

        $userData = [
            'name' => $validated['name'],
            'phone' => $validated['phone'],
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($userData);

        return back()->with('success', 'Profile updated successfully.');
    }
}
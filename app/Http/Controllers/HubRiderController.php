<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Rider;
use App\Models\Hub;
use App\Models\Franchise;
use Illuminate\Validation\Rule;

class HubRiderController extends Controller
{
    private function getAuthScope()
    {
        $user = Auth::user();
        
        $franchise = Franchise::where('user_id', $user->id)->first();
        if ($franchise && $franchise->status !== 'approved') {
            abort(403, 'Your franchise application is pending Admin approval.');
        }

        $hubs = Hub::where('is_active', true)
            ->where(function($q) use ($user) {
                $q->where('manager_id', $user->id);
            })->get();

        if (!$franchise && $hubs->isEmpty()) {
            abort(403, 'You are not a Franchise owner or Hub Manager.');
        }

        return [
            'user' => $user,
            'franchise_id' => $franchise ? $franchise->id : null,
            'hubs' => $hubs,
        ];
    }

        public function index()
    {
        $hubManagerId = Auth::id();
        $hub = \App\Models\Hub::where('manager_id', $hubManagerId)->first();
        if(!$hub) {
            return redirect()->route('hub.dashboard')->with('error', 'No Hub Assigned.');
        }

        $riders = Rider::where('hub_id', $hub->id)
                      ->with(['user'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(15);
                      
        // Append live performance stats to each rider
        foreach($riders as $rider) {
            if(!$rider->user) continue;
            
            $todayShipments = \App\Models\Shipment::where('rider_id', $rider->user->id)
                ->where('status', 'Delivered')
                ->whereDate('updated_at', today())
                ->get();
                
            $rider->today_deliveries = $todayShipments->count();
            
            $rider->today_cash = $todayShipments->where('is_cod', 1)->filter(function($s) {
                return empty($s->payment_type) || $s->payment_type === 'Cash';
            })->sum(function($s) {
                return $s->cod_amount > 0 ? $s->cod_amount : $s->invoice_value;
            });
            
            $rider->today_upi = $todayShipments->where('payment_type', 'UPI')->sum(function($s) {
                return $s->cod_amount > 0 ? $s->cod_amount : $s->invoice_value;
            });
        }
        
        return view('hub.fleet.index', compact('riders', 'hub'));
    }

    public function store(Request $request)
    {
        $scope = $this->getAuthScope();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6',
            'role' => 'required|in:rider,pickup_rider,delivery_rider',
            'hub_id' => 'nullable|exists:hubs,id',
            'vehicle_type' => 'nullable|string|max:100',
            'vehicle_number' => 'nullable|string|max:100',
        ]);

        if ($validated['hub_id']) {
            $hub = Hub::find($validated['hub_id']);
            if ($hub->manager_id !== $scope['user']->id) {
                return back()->with('error', 'UNAUTHORIZED: You can only assign riders to hubs you manage.');
            }
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role']
        ]);

        Rider::create([
            'user_id' => $user->id,
            'franchise_id' => $scope['franchise_id'],
            'hub_id' => $validated['hub_id'] ?? null,
            'vehicle_type' => $validated['vehicle_type'] ?? null,
            'vehicle_number' => $validated['vehicle_number'] ?? null,
            'status' => 'active',
            'is_active' => true
        ]);

        return back()->with('success', 'Rider created successfully.');
    }

    public function update(Request $request, $id)
    {
        $scope = $this->getAuthScope();
        
        $rider = Rider::with('user')->findOrFail($id);
        
        if ($scope['franchise_id']) {
            if ($rider->franchise_id !== $scope['franchise_id']) abort(403, 'UNAUTHORIZED');
        } else {
            if ($rider->franchise_id !== null || !in_array($rider->hub_id, $scope['hubs']->pluck('id')->toArray())) {
                abort(403, 'UNAUTHORIZED');
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', Rule::unique('users')->ignore($rider->user_id)],
            'password' => 'nullable|string|min:6',
            'hub_id' => 'nullable|exists:hubs,id',
            'vehicle_type' => 'nullable|string|max:100',
            'vehicle_number' => 'nullable|string|max:100',
            'status' => 'required|in:active,suspended',
        ]);

        if ($validated['hub_id']) {
            $hub = Hub::find($validated['hub_id']);
            if ($hub->manager_id !== $scope['user']->id) {
                return back()->with('error', 'UNAUTHORIZED: You can only assign riders to hubs you manage.');
            }
        }

        $userData = [
            'name' => $validated['name'],
            'phone' => $validated['phone']
        ];
        
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }
        
        $rider->user->update($userData);

        $rider->update([
            'hub_id' => $validated['hub_id'],
            'vehicle_type' => $validated['vehicle_type'] ?? null,
            'vehicle_number' => $validated['vehicle_number'] ?? null,
            'status' => $validated['status'],
            'is_active' => ($validated['status'] === 'active')
        ]);

        return back()->with('success', 'Rider updated successfully.');
    }

    public function destroy($id)
    {
        $scope = $this->getAuthScope();
        $rider = Rider::with("user")->findOrFail($id);
        
        if ($scope["franchise_id"]) {
            if ($rider->franchise_id !== $scope["franchise_id"]) abort(403, "UNAUTHORIZED");
        } else {
            if ($rider->franchise_id !== null || !in_array($rider->hub_id, $scope["hubs"]->pluck("id")->toArray())) {
                abort(403, "UNAUTHORIZED");
            }
        }

        // Delete the user record, which cascades to delete the rider record
        $rider->user->delete();

        return back()->with("success", "Rider deleted successfully.");
    }

    public function report(Request $request, $id)
    {
        $rider = Rider::with('user')->findOrFail($id);
        $userId = $rider->user->id;

        $selectedDate = $request->query('date', today()->toDateString());

        $shipments = \App\Models\Shipment::where('rider_id', $userId)
                        ->where('status', 'Delivered')
                        ->whereDate('updated_at', $selectedDate)
                        ->orderBy('updated_at', 'desc')
                        ->get();

        $totalDeliveries = $shipments->count();

        $totalCash = $shipments->where('is_cod', 1)->filter(function($s) {
            return empty($s->payment_type) || $s->payment_type === 'Cash';
        })->sum(function($s) {
            return $s->cod_amount > 0 ? $s->cod_amount : $s->invoice_value;
        });

        $totalUpi = $shipments->where('payment_type', 'UPI')->sum(function($s) {
            return $s->cod_amount > 0 ? $s->cod_amount : $s->invoice_value;
        });

        return view('hub.fleet.report', compact('rider', 'shipments', 'selectedDate', 'totalDeliveries', 'totalCash', 'totalUpi'));
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\Franchise;
use App\Models\Hub;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HubReportController extends Controller
{
    private function getAuthScope()
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthenticated');
        }

        $franchise = Franchise::where('user_id', $user->id)->first();
        $isHubManager = Hub::where('manager_id', $user->id)->exists();
        
        $isAuthorized = $user->isAdmin()
            || in_array($user->role, ['super_admin', 'admin', 'franchise'])
            || $franchise
            || $isHubManager;

        if (!$isAuthorized) {
            abort(403, 'Unauthorized');
        }

        return [
            'user' => $user,
            'franchise' => $franchise,
            'hub' => Hub::where('manager_id', $user->id)->first()
        ];
    }

    private function getScopedQuery()
    {
        $scope = $this->getAuthScope();
        $franchise = $scope['franchise'];
        $hub = $scope['hub'];
        $query = Shipment::query();

        if ($franchise) {
            if ($franchise->isState()) {
                $districtIds = $franchise->districtFranchises()->pluck('id')->toArray();
                $query->where(function($q) use ($franchise, $districtIds) {
                    $q->where('state_franchise_id', $franchise->id)
                      ->orWhere('franchise_id', $franchise->id)
                      ->orWhere('pickup_franchise_id', $franchise->id)
                      ->orWhere('delivery_state', $franchise->state)
                      ->orWhere('user_id', Auth::id());
                    if (!empty($districtIds)) {
                        $q->orWhereIn('franchise_id', $districtIds)
                          ->orWhereIn('pickup_franchise_id', $districtIds);
                    }
                });
            } else {
                $pincodes = is_array($franchise->serviceable_pincodes) ? $franchise->serviceable_pincodes : (json_decode($franchise->serviceable_pincodes, true) ?? []);
                if (!is_array($pincodes)) { $pincodes = $pincodes ? [$pincodes] : []; }

                $query->where(function($q) use ($franchise, $pincodes) {
                    $q->where('franchise_id', $franchise->id)
                      ->orWhere('pickup_franchise_id', $franchise->id)
                      ->orWhere('user_id', Auth::id());
                    if (!empty($pincodes)) {
                        $q->orWhereIn('pickup_pincode', $pincodes)
                          ->orWhereIn('delivery_pincode', $pincodes);
                    }
                });
            }
        } elseif ($hub) {
            $query->where(function($q) use ($hub) {
                $q->where('pickup_city', $hub->city)
                  ->orWhere('delivery_city', $hub->city);
            });
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->getScopedQuery();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Generate Reports Data
        $totalBookings = (clone $query)->count();
        $delivered = (clone $query)->where('status', 'Delivered')->count();
        $rto = (clone $query)->where('status', 'RTO')->count();
        $ndr = (clone $query)->where('status', 'NDR')->count();
        
        // Pickups vs Deliveries logic
        $scope = $this->getAuthScope();
        if ($scope['franchise']) {
            if ($scope['franchise']->isState()) {
                $districtIds = $scope['franchise']->districtFranchises()->pluck('id')->toArray();
                $myPickups = (clone $query)->where(function($q) use ($scope, $districtIds) {
                    $q->where('pickup_franchise_id', $scope['franchise']->id);
                    if (!empty($districtIds)) {
                        $q->orWhereIn('pickup_franchise_id', $districtIds);
                    }
                })->count();

                $myDeliveries = (clone $query)->where(function($q) use ($scope, $districtIds) {
                    $q->where('franchise_id', $scope['franchise']->id);
                    if (!empty($districtIds)) {
                        $q->orWhereIn('franchise_id', $districtIds);
                    }
                })->count();
            } else {
                $p = $scope['franchise']->serviceable_pincodes;
                $pincodes = is_array($p) ? $p : (json_decode($p, true) ?? []);
                if(!is_array($pincodes)) $pincodes = $pincodes ? [$pincodes] : [];

                $myPickups = (clone $query)->where(function($q) use ($pincodes, $scope) {
                    $q->where('pickup_franchise_id', $scope['franchise']->id);
                    if (!empty($pincodes)) { $q->orWhereIn('pickup_pincode', $pincodes); }
                })->count();

                $myDeliveries = (clone $query)->where(function($q) use ($pincodes, $scope) {
                    $q->where('franchise_id', $scope['franchise']->id);
                    if (!empty($pincodes)) { $q->orWhereIn('delivery_pincode', $pincodes); }
                })->count();
            }
        } else {
            $myPickups = (clone $query)->where('pickup_city', $scope['hub']?->city)->count();
            $myDeliveries = (clone $query)->where('delivery_city', $scope['hub']?->city)->count();
        }

        // Pending COD calculated using merchandise COD value
        $pendingCod = (clone $query)->where('status', 'Delivered')
            ->where(function($q) { $q->where('payment_type', 'COD')->orWhere('is_cod', 1); })
            ->where(function($q) { $q->where('cod_remitted', 0)->orWhereNull('cod_remitted'); })
            ->sum(DB::raw('COALESCE(cod_amount, invoice_value, 0)')) ?? 0;

        return view('hub.reports.index', compact('totalBookings', 'delivered', 'rto', 'ndr', 'myPickups', 'myDeliveries', 'pendingCod'));
    }

    public function exportCsv(Request $request)
    {
        $query = $this->getScopedQuery();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $fileName = 'hub_shipments_report_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($query) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'ID', 'AWB Number', 'Customer/Seller Name', 'Receiver Name', 
                'Receiver Phone', 'City', 'Pincode', 
                'Type', 'COD Amount', 'Status', 'Date'
            ]);

            $query->with('user')->orderBy('created_at', 'desc')->chunk(500, function($shipments) use ($file) {
                foreach ($shipments as $s) {
                    fputcsv($file, [
                        $s->id,
                        $s->awb_number,
                        $s->user->name ?? 'Unknown',
                        $s->receiver_name,
                        $s->receiver_phone,
                        $s->delivery_city,
                        $s->delivery_pincode,
                        ($s->payment_type === 'COD' || $s->is_cod) ? 'COD' : 'Prepaid',
                        $s->total_amount ?? $s->invoice_value,
                        $s->status,
                        $s->created_at->format('Y-m-d H:i')
                    ]);
                }
            });

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}

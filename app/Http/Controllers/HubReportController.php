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
        $franchise = Franchise::where('user_id', $user->id)->first();
        $isHubManager = Hub::where('manager_id', $user->id)->exists();
        
        if (!$franchise && !$isHubManager) {
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
        
        $pincodes = [];
        $city = null;
        
        if ($franchise) {
            $pincodes = is_array($franchise->serviceable_pincodes) ? $franchise->serviceable_pincodes : (json_decode($franchise->serviceable_pincodes, true) ?? []);
            if (!is_array($pincodes)) { $pincodes = $pincodes ? [$pincodes] : []; }
        } elseif ($hub) {
            $city = $hub->city;
        }

        $query = Shipment::query();

        $query->where(function($q) use ($franchise, $pincodes, $city) {
            if ($franchise) {
                $q->where('franchise_id', $franchise->id);
                if (!empty($pincodes)) {
                    $q->orWhereIn('pickup_pincode', $pincodes)
                      ->orWhereIn('delivery_pincode', $pincodes);
                }
            } else {
                if ($city) {
                    $q->where('pickup_city', $city)
                      ->orWhere('delivery_city', $city);
                }
            }
        });

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
        
        // Pickups vs Deliveries logic (rough approximation)
        $scope = $this->getAuthScope();
        $pincodes = [];
        if ($scope['franchise']) {
             $p = $scope['franchise']->serviceable_pincodes;
             $pincodes = is_array($p) ? $p : (json_decode($p, true) ?? []);
             if(!is_array($pincodes)) $pincodes = $pincodes ? [$pincodes] : [];
        }

        $myPickups = (clone $query)->where(function($q) use ($pincodes, $scope) {
            if (!empty($pincodes)) { $q->whereIn('pickup_pincode', $pincodes); }
            elseif ($scope['hub']) { $q->where('pickup_city', $scope['hub']->city); }
        })->count();

        $myDeliveries = (clone $query)->where(function($q) use ($pincodes, $scope) {
            if (!empty($pincodes)) { $q->whereIn('delivery_pincode', $pincodes); }
            elseif ($scope['hub']) { $q->where('delivery_city', $scope['hub']->city); }
        })->count();

        // Pending COD
        $pendingCod = (clone $query)->where('status', 'Delivered')
            ->where(function($q) { $q->where('payment_type', 'COD')->orWhere('is_cod', 1); })
            ->where(function($q) { $q->where('cod_remitted', 0)->orWhereNull('cod_remitted'); })
            ->sum('total_amount');

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

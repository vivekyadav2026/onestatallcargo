<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class AdminShipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Shipment::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('awb_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $shipments = $query->paginate(15)->appends($request->all());

        return view('admin.shipments.index', compact('shipments'));
    }
}

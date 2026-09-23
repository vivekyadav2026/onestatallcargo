<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class AdminShipmentController extends Controller
{
    public function index()
    {
        // For now, load shipments with pagination. Order by most recent.
        $shipments = Shipment::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.shipments.index', compact('shipments'));
    }
}

<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Shipment;
class AdminNDRController extends Controller {
    public function index() {
        $shipments = Shipment::where('status', 'NDR')->with('user')->paginate(15);
        return view('admin.ndr.index', compact('shipments'));
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class AdminEvidenceController extends Controller
{
    public function index(Request $request)
    {
        $query = Shipment::whereNotNull('video_evidence_url')->with(['user', 'assignedRider']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('awb_number', 'LIKE', "%{$search}%");
        }

        $evidences = $query->orderBy('updated_at', 'desc')->paginate(15)->appends($request->all());
            
        return view('admin.evidence.index', compact('evidences'));
    }
}

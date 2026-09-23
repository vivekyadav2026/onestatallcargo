<?php
namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class AdminEvidenceController extends Controller
{
    public function index()
    {
        // Fetch all shipments that have video evidence uploaded by the Rider
        $evidences = Shipment::whereNotNull('video_evidence_url')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);
            
        return view('admin.evidence.index', compact('evidences'));
    }
}

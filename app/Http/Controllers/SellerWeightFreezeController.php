<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeightFreeze;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellerWeightFreezeController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'all');
        $search = $request->query('search', '');
        
        $query = WeightFreeze::where('user_id', Auth::id());
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        if ($tab === 'requested') {
            $query->where('status', 'requested');
        } elseif ($tab === 'accepted') {
            $query->where('status', 'accepted');
        } elseif ($tab === 'rejected') {
            $query->where('status', 'rejected');
        }
        
        $freezes = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->all());

        return view('seller.weight.freeze', compact('freezes', 'tab', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'length' => 'required|numeric|min:0.1',
            'width' => 'required|numeric|min:0.1',
            'height' => 'required|numeric|min:0.1',
            'weight' => 'required|numeric|min:0.01',
            'proof_images.*' => 'nullable|image|max:5120'
        ]);

        $imagePaths = [];
        if ($request->hasFile('proof_images')) {
            foreach ($request->file('proof_images') as $file) {
                $imagePaths[] = $file->store('evidence', 'public');
            }
        }

        WeightFreeze::create([
            'user_id' => Auth::id(),
            'product_name' => $request->product_name,
            'sku' => $request->sku,
            'length' => $request->length,
            'width' => $request->width,
            'height' => $request->height,
            'weight' => $request->weight,
            'packaging_image' => json_encode($imagePaths),
            'status' => 'requested'
        ]);

        return back()->with('success', 'Weight Freeze requested successfully.');
    }

    public function export(Request $request)
    {
        $freezes = WeightFreeze::where('user_id', Auth::id())->get();
        
        $csvData = "Product Name,SKU,Length (cm),Width (cm),Height (cm),Weight (kg),Status,Created At\n";
        foreach ($freezes as $freeze) {
            $csvData .= sprintf(
                "\"%s\",\"%s\",%s,%s,%s,%s,%s,\"%s\"\n",
                str_replace('"', '""', $freeze->product_name),
                str_replace('"', '""', $freeze->sku),
                $freeze->length,
                $freeze->width,
                $freeze->height,
                $freeze->weight,
                $freeze->status,
                $freeze->created_at->format('Y-m-d H:i:s')
            );
        }
        
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="weight_freezes.csv"');
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,txt'
        ]);

        $path = $request->file('import_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $header = array_shift($data);

        $count = 0;
        foreach ($data as $row) {
            if (count($row) < 6) continue;
            
            WeightFreeze::create([
                'user_id' => Auth::id(),
                'product_name' => $row[0] ?? 'Unknown',
                'sku' => $row[1] ?? null,
                'length' => (float)($row[2] ?? 0),
                'width' => (float)($row[3] ?? 0),
                'height' => (float)($row[4] ?? 0),
                'weight' => (float)($row[5] ?? 0),
                'status' => 'requested'
            ]);
            $count++;
        }

        return back()->with('success', "$count products imported successfully.");
    }
}


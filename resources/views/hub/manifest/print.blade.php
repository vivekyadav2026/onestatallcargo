<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manifest: {{ $manifest->manifest_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Nunito', sans-serif; background: white; color: black; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; margin: 0; }
        }
    </style>
</head>
<body class="p-8 max-w-4xl mx-auto">
    <div class="no-print mb-8 flex justify-end">
        <button onclick="window.print()" class="px-6 py-2 bg-gray-900 text-white font-bold rounded shadow-md">Print Manifest</button>
    </div>

    <div class="flex justify-between items-start border-b-2 border-black pb-6 mb-6">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tighter">OneStall Cargo</h1>
            <p class="font-bold text-gray-600 mt-1">DISPATCH MANIFEST</p>
        </div>
        <div class="text-right">
            <div class="text-2xl font-bold">{{ $manifest->manifest_number }}</div>
            <div class="text-sm mt-1">Date: {{ $manifest->created_at->format('d M Y, H:i') }}</div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-8 mb-8">
        <div>
            <h3 class="text-xs font-bold text-gray-500 uppercase">From (Source Hub)</h3>
            <p class="font-bold text-lg">{{ $manifest->sourceHub->name ?? 'Unknown Hub' }}</p>
            <p class="text-sm">{{ $manifest->sourceHub->city ?? '' }} ({{ $manifest->sourceHub->pincode ?? '' }})</p>
        </div>
        <div>
            <h3 class="text-xs font-bold text-gray-500 uppercase">To (Destination)</h3>
            <p class="font-bold text-lg">{{ $manifest->destination }}</p>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-8 bg-gray-100 p-4 rounded-lg">
        <div>
            <div class="text-xs font-bold text-gray-500 uppercase">Bag Number</div>
            <div class="font-bold">{{ $manifest->bag->bag_number }}</div>
        </div>
        <div>
            <div class="text-xs font-bold text-gray-500 uppercase">Total Shipments</div>
            <div class="font-bold">{{ $manifest->shipment_count }}</div>
        </div>
        <div>
            <div class="text-xs font-bold text-gray-500 uppercase">Total Weight</div>
            <div class="font-bold">{{ $manifest->bag->weight_kg }} kg</div>
        </div>
    </div>

    <table class="w-full text-left text-sm mb-12">
        <thead class="border-b-2 border-black">
            <tr>
                <th class="py-2">#</th>
                <th class="py-2">AWB Number</th>
                <th class="py-2">Order ID</th>
                <th class="py-2">Destination Pincode</th>
                <th class="py-2">Weight</th>
                <th class="py-2 text-right">Payment</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($manifest->bag->shipments as $index => $shipment)
            <tr>
                <td class="py-2">{{ $index + 1 }}</td>
                <td class="py-2 font-bold">{{ $shipment->awb_number }}</td>
                <td class="py-2">{{ $shipment->order_id }}</td>
                <td class="py-2">{{ $shipment->delivery_pincode }}</td>
                <td class="py-2">{{ $shipment->weight_kg }} kg</td>
                <td class="py-2 text-right">{{ $shipment->is_cod ? 'COD' : 'PREPAID' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-16 grid grid-cols-2 gap-16 pt-8">
        <div>
            <div class="border-t border-black pt-2 font-bold text-center">Dispatched By (Signature)</div>
            <div class="text-center text-sm mt-1">{{ $manifest->creator->name ?? 'Hub Manager' }}</div>
        </div>
        <div>
            <div class="border-t border-black pt-2 font-bold text-center">Received By (Signature)</div>
            <div class="text-center text-sm mt-1">Date & Time: __________________</div>
        </div>
    </div>
</body>
</html>


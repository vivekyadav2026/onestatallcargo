<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Commercial Invoice - {{ $shipment->awb_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 py-10">
    <div class="max-w-4xl mx-auto bg-white border border-gray-300 p-10 shadow-lg relative">
        <!-- Print Button -->
        <button onclick="window.print()" class="no-print absolute top-4 right-4 bg-purple-600 text-white px-4 py-2 rounded font-bold shadow hover:bg-purple-700">
            <i class="fa-solid fa-print"></i> Print Invoice
        </button>

        <!-- Header -->
        <div class="text-center border-b-2 border-gray-800 pb-6 mb-6">
            <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">Commercial Invoice</h1>
            <p class="text-xs text-gray-600 font-bold tracking-widest mt-1">For Customs & Export Purposes Only</p>
        </div>

        <div class="flex justify-between items-start mb-6 text-sm">
            <div>
                <span class="font-bold text-gray-500">AWB No:</span> <span class="font-black text-lg text-gray-900">{{ $shipment->awb_number }}</span>
            </div>
            <div class="text-right">
                <span class="font-bold text-gray-500">Date:</span> <span class="font-bold text-gray-900">{{ $shipment->created_at->format('d M Y') }}</span><br>
                <span class="font-bold text-gray-500">Terms:</span> <span class="font-bold text-gray-900">DDU (Delivered Duty Unpaid)</span>
            </div>
        </div>

        <!-- Addresses -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <!-- Shipper -->
            <div class="border border-gray-300 p-4 rounded-lg">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200 pb-2 mb-2">Shipper (Exporter)</h3>
                <p class="font-black text-lg text-gray-900">{{ Auth::user()->name }}</p>
                <p class="text-sm text-gray-700 mt-1">Ph: {{ Auth::user()->phone ?? 'N/A' }}</p>
                <p class="text-sm text-gray-700 mt-1 font-bold">Country of Origin: INDIA</p>
            </div>
            
            <!-- Consignee -->
            <div class="border border-gray-300 p-4 rounded-lg">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200 pb-2 mb-2">Consignee (Importer)</h3>
                <p class="font-black text-lg text-gray-900">{{ $shipment->receiver_name }}</p>
                <p class="text-sm text-gray-700 mt-1">{{ $shipment->delivery_address }}<br>{{ $shipment->delivery_city }} - {{ $shipment->delivery_pincode }}</p>
                <p class="text-sm text-gray-700 mt-1 font-bold">Destination Country: {{ $shipment->destination_country ?? 'N/A' }}</p>
                <p class="text-sm text-gray-700 mt-1">Ph: {{ $shipment->delivery_phone }}</p>
            </div>
        </div>

        <!-- Itemized Goods -->
        <div class="border border-gray-300 rounded-lg overflow-hidden mb-8">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-gray-100 text-gray-800 uppercase tracking-wider text-[10px] font-bold border-b border-gray-300">
                        <th class="px-4 py-3">Qty</th>
                        <th class="px-4 py-3">Description of Goods</th>
                        <th class="px-4 py-3">HS Code</th>
                        <th class="px-4 py-3">Weight</th>
                        <th class="px-4 py-3 text-right">Unit Value</th>
                        <th class="px-4 py-3 text-right">Total Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="px-4 py-4 text-center">{{ $shipment->packages_count ?? 1 }}</td>
                        <td class="px-4 py-4 font-bold text-gray-700">General Export Goods</td>
                        <td class="px-4 py-4 font-mono text-gray-500">{{ $shipment->hs_code ?? '9999.99' }}</td>
                        <td class="px-4 py-4">{{ $shipment->weight_kg }} KG</td>
                        <td class="px-4 py-4 text-right">USD {{ number_format($shipment->customs_value ?? $shipment->invoice_value, 2) }}</td>
                        <td class="px-4 py-4 text-right font-bold text-gray-900">USD {{ number_format($shipment->customs_value ?? $shipment->invoice_value, 2) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 border-t-2 border-gray-800">
                        <td colspan="5" class="px-4 py-3 text-right font-bold uppercase tracking-widest text-xs text-gray-600">Total Declared Value:</td>
                        <td class="px-4 py-3 text-right font-black text-lg text-gray-900">USD {{ number_format($shipment->customs_value ?? $shipment->invoice_value, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Declarations -->
        <div class="text-xs text-gray-600 space-y-2 mb-6">
            <p><strong>Reason for Export:</strong> Commercial</p>
            <p>I declare that all the information contained in this invoice to be true and correct. I declare that the goods are of India origin.</p>
        </div>

        <!-- Footer / Signatures -->
        <div class="grid grid-cols-2 gap-8 text-sm pt-8 border-t border-gray-300">
            <div>
                <p class="font-bold text-gray-600 uppercase text-xs tracking-wider mb-8">Authorized Signature (Shipper)</p>
                <div class="border-b border-gray-400 w-3/4 mb-2"></div>
                <p class="font-bold text-gray-900">{{ Auth::user()->name }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-400 italic mt-8">System Generated via OneStall Cargo API</p>
            </div>
        </div>
    </div>
</body>
</html>

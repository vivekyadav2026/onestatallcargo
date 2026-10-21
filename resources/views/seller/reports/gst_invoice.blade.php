<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GST Tax Invoice - {{ $invoiceNumber }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background-color: #ffffff; }
            .no-print { display: none !important; }
            .print-shadow-none { box-shadow: none !important; border-color: #000 !important; }
        }
        @page {
            size: A4 portrait;
            margin: 12mm;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 antialiased font-sans py-8">

    <!-- Action Toolbar (Hidden during print) -->
    <div class="no-print max-w-4xl mx-auto mb-6 flex justify-between items-center bg-white p-4 rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-3">
            <a href="{{ route('seller.reports.index', ['month' => $month]) }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg text-xs transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back to Reports
            </a>
            <span class="text-xs text-gray-500 font-medium">Billing Period: <strong class="text-gray-900">{{ $billingPeriod }}</strong></span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 bg-[#4338ca] hover:bg-[#3730a3] text-white font-extrabold rounded-lg text-xs shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Print / Save as PDF
            </button>
        </div>
    </div>

    <!-- Official Tax Invoice Document (A4) -->
    <div class="max-w-4xl mx-auto bg-white border border-gray-300 shadow-xl p-8 sm:p-10 rounded-lg print-shadow-none text-[12px] leading-relaxed">
        
        <!-- Header Banner -->
        <div class="border-b-2 border-gray-900 pb-5 mb-5 flex justify-between items-start">
            <div>
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 bg-gray-900 text-white rounded-lg flex items-center justify-center font-black text-lg tracking-wider">
                        OS
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-gray-900 uppercase tracking-tight">{{ $platform['legal_name'] }}</h1>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Courier, Freight & Logistics Services Division</p>
                    </div>
                </div>
                <div class="mt-3 text-gray-600 text-[11px] space-y-0.5">
                    <p>{{ $platform['address'] }}</p>
                    <p>{{ $platform['city'] }}, {{ $platform['state'] }} - {{ $platform['pincode'] }}</p>
                    <p><strong class="text-gray-900">GSTIN:</strong> <span class="font-mono font-bold">{{ $platform['gstin'] }}</span> | <strong class="text-gray-900">PAN:</strong> {{ $platform['pan'] }}</p>
                    <p><strong class="text-gray-900">Email:</strong> {{ $platform['email'] }} | <strong class="text-gray-900">Phone:</strong> {{ $platform['phone'] }}</p>
                </div>
            </div>

            <div class="text-right">
                <div class="inline-block bg-gray-900 text-white px-3 py-1 rounded text-[11px] font-black uppercase tracking-widest mb-2">
                    TAX INVOICE
                </div>
                <p class="text-[10px] text-gray-400 font-bold uppercase">Original for Recipient</p>
                
                <div class="mt-3 space-y-1 text-[11px]">
                    <div>
                        <span class="text-gray-500 font-bold">Invoice No:</span>
                        <span class="font-mono font-black text-gray-900 text-[13px] ml-1">{{ $invoiceNumber }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 font-bold">Invoice Date:</span>
                        <span class="font-bold text-gray-900 ml-1">{{ $invoiceDate }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 font-bold">Billing Month:</span>
                        <span class="font-bold text-indigo-700 ml-1">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 font-bold">Place of Supply:</span>
                        <span class="font-bold text-gray-900 ml-1">{{ $seller['state'] }} ({{ $seller['state_code'] }})</span>
                    </div>
                    <div>
                        <span class="text-gray-500 font-bold">Reverse Charge (RCM):</span>
                        <span class="font-bold text-gray-900 ml-1">No</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Billed To & Shipped From Grid -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <!-- Billed To (Seller) -->
            <div class="border border-gray-300 rounded-lg p-4 bg-gray-50/50">
                <div class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 border-b border-gray-200 pb-1 mb-2">
                    Billed To (Client / Seller)
                </div>
                <p class="text-sm font-black text-gray-900">{{ $seller['name'] }}</p>
                @if($seller['brand'] && $seller['brand'] !== $seller['name'])
                    <p class="text-[11px] text-gray-600 font-semibold">Trade Name: {{ $seller['brand'] }}</p>
                @endif
                <p class="text-gray-600 mt-1">{{ $seller['address'] }}</p>
                <p class="text-gray-600">{{ $seller['city'] ? $seller['city'] . ', ' : '' }}{{ $seller['state'] }} {{ $seller['pincode'] ? '- ' . $seller['pincode'] : '' }}</p>
                
                <div class="mt-2 pt-2 border-t border-gray-200 text-[11px] space-y-0.5">
                    <p><strong class="text-gray-900">GSTIN:</strong> <span class="font-mono font-bold text-indigo-900">{{ $seller['gstin'] }}</span></p>
                    <p><strong class="text-gray-900">PAN:</strong> {{ $seller['pan'] }} | <strong class="text-gray-900">State Code:</strong> {{ $seller['state_code'] }}</p>
                    <p><strong class="text-gray-900">Contact:</strong> {{ $seller['phone'] }} | {{ $seller['email'] }}</p>
                </div>
            </div>

            <!-- Service & Dispatch Details -->
            <div class="border border-gray-300 rounded-lg p-4 bg-gray-50/50">
                <div class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 border-b border-gray-200 pb-1 mb-2">
                    Billing & Logistics Period Summary
                </div>
                <div class="space-y-1.5 text-[11px]">
                    <div class="flex justify-between py-1 border-b border-gray-100">
                        <span class="text-gray-500 font-bold">Billing Cycle:</span>
                        <span class="font-semibold text-gray-800">{{ $billingPeriod }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-100">
                        <span class="text-gray-500 font-bold">Total Consignments Billed:</span>
                        <span class="font-black text-gray-900">{{ $totalConsignments }} Shipments</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-100">
                        <span class="text-gray-500 font-bold">Total Billable Weight:</span>
                        <span class="font-bold text-gray-800">{{ number_format($totalWeight, 2) }} KG</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-100">
                        <span class="text-gray-500 font-bold">Tax Nature:</span>
                        <span class="font-bold text-indigo-700">
                            {{ $isIntraState ? 'Intra-State Supply (CGST + SGST)' : 'Inter-State Supply (IGST)' }}
                        </span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-gray-500 font-bold">Payment Terms:</span>
                        <span class="font-semibold text-gray-800">Prepaid Wallet Deducted / Remitted</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Line Items Table -->
        <div class="border border-gray-900 rounded-lg overflow-hidden mb-6">
            <table class="w-full text-left text-[11px]">
                <thead class="bg-gray-900 text-white uppercase text-[10px] tracking-wider font-extrabold">
                    <tr>
                        <th class="p-2.5 text-center w-10">#</th>
                        <th class="p-2.5">Description of Service</th>
                        <th class="p-2.5 text-center">HSN/SAC</th>
                        <th class="p-2.5 text-center">Qty (Shipments)</th>
                        <th class="p-2.5 text-right">Taxable Value</th>
                        @if($isIntraState)
                            <th class="p-2.5 text-right">CGST (9%)</th>
                            <th class="p-2.5 text-right">SGST (9%)</th>
                        @else
                            <th class="p-2.5 text-right">IGST (18%)</th>
                        @endif
                        <th class="p-2.5 text-right">Total Amount (₹)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-800">
                    <tr>
                        <td class="p-3 text-center font-bold">1</td>
                        <td class="p-3">
                            <div class="font-bold text-gray-900">Freight & Logistics Courier Services</div>
                            <div class="text-[10px] text-gray-500">Transportation of goods by road across domestic zones (Month: {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('M Y') }})</div>
                        </td>
                        <td class="p-3 text-center font-mono font-bold">{{ $platform['sac_code'] }}</td>
                        <td class="p-3 text-center font-semibold">{{ $totalConsignments }}</td>
                        <td class="p-3 text-right font-semibold">₹{{ number_format($taxableValue, 2) }}</td>
                        @if($isIntraState)
                            <td class="p-3 text-right font-semibold">₹{{ number_format($cgstAmount, 2) }}</td>
                            <td class="p-3 text-right font-semibold">₹{{ number_format($sgstAmount, 2) }}</td>
                        @else
                            <td class="p-3 text-right font-semibold">₹{{ number_format($igstAmount, 2) }}</td>
                        @endif
                        <td class="p-3 text-right font-black text-gray-900">₹{{ number_format($totalFreightBilled, 2) }}</td>
                    </tr>
                </tbody>
                <tfoot class="bg-gray-100 font-extrabold text-[11px] border-t-2 border-gray-900">
                    <tr>
                        <td colspan="4" class="p-2.5 text-right uppercase tracking-wider text-gray-600">Subtotal</td>
                        <td class="p-2.5 text-right">₹{{ number_format($taxableValue, 2) }}</td>
                        @if($isIntraState)
                            <td class="p-2.5 text-right">₹{{ number_format($cgstAmount, 2) }}</td>
                            <td class="p-2.5 text-right">₹{{ number_format($sgstAmount, 2) }}</td>
                        @else
                            <td class="p-2.5 text-right">₹{{ number_format($igstAmount, 2) }}</td>
                        @endif
                        <td class="p-2.5 text-right text-gray-900">₹{{ number_format($totalFreightBilled, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Totals & Tax Summary Breakdown -->
        <div class="grid grid-cols-2 gap-6 mb-6 items-start">
            
            <!-- Left: Tax Summary Box -->
            <div class="border border-gray-300 rounded-lg p-3 bg-gray-50 text-[11px]">
                <div class="font-extrabold text-gray-700 uppercase tracking-wider text-[10px] mb-2 border-b border-gray-200 pb-1">
                    GST Tax Analysis (SAC {{ $platform['sac_code'] }})
                </div>
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] text-gray-500 font-bold border-b border-gray-200">
                            <th class="py-1">Tax Type</th>
                            <th class="py-1 text-center">Rate</th>
                            <th class="py-1 text-right">Tax Amount (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @if($isIntraState)
                            <tr>
                                <td class="py-1 text-gray-700">Central Tax (CGST)</td>
                                <td class="py-1 text-center font-mono">9.00%</td>
                                <td class="py-1 text-right font-semibold">₹{{ number_format($cgstAmount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 text-gray-700">State Tax (SGST)</td>
                                <td class="py-1 text-center font-mono">9.00%</td>
                                <td class="py-1 text-right font-semibold">₹{{ number_format($sgstAmount, 2) }}</td>
                            </tr>
                        @else
                            <tr>
                                <td class="py-1 text-gray-700">Integrated Tax (IGST)</td>
                                <td class="py-1 text-center font-mono">18.00%</td>
                                <td class="py-1 text-right font-semibold">₹{{ number_format($igstAmount, 2) }}</td>
                            </tr>
                        @endif
                        <tr class="font-bold border-t border-gray-300">
                            <td class="py-1 text-gray-900">Total Tax</td>
                            <td class="py-1 text-center font-mono">18.00%</td>
                            <td class="py-1 text-right text-indigo-700 font-black">₹{{ number_format($totalGst, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Right: Grand Total Card -->
            <div class="border-2 border-gray-900 rounded-lg p-4 bg-white space-y-2">
                <div class="flex justify-between text-gray-600 font-medium">
                    <span>Total Taxable Amount:</span>
                    <span class="font-bold text-gray-800">₹{{ number_format($taxableValue, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-600 font-medium">
                    <span>Total GST (18%):</span>
                    <span class="font-bold text-indigo-700">₹{{ number_format($totalGst, 2) }}</span>
                </div>
                <div class="flex justify-between text-base font-black text-gray-900 pt-2 border-t-2 border-gray-900">
                    <span>Invoice Total (INR):</span>
                    <span class="text-xl">₹{{ number_format($totalFreightBilled, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Amount in Words -->
        <div class="p-3 bg-indigo-50/70 border border-indigo-100 rounded-lg mb-6 text-[11px]">
            <span class="font-extrabold text-indigo-900 uppercase tracking-wide">Total Amount in Words:</span>
            <span class="font-bold text-gray-900 ml-1 italic">{{ $amountInWords }}</span>
        </div>

        <!-- Declarations & Signatures -->
        <div class="grid grid-cols-2 gap-8 pt-4 border-t border-gray-300 text-[10px] leading-normal">
            <div>
                <h4 class="font-bold uppercase tracking-wider text-gray-700 mb-1">Terms & Conditions:</h4>
                <ol class="list-decimal pl-4 space-y-0.5 text-gray-500">
                    <li>This invoice is issued for logistics, freight and transportation services rendered during the billing cycle.</li>
                    <li>Input Tax Credit (ITC) can be claimed by the recipient subject to valid GSTIN submission and reconciliation in GSTR-2B.</li>
                    <li>Freight charges are prepaid and reconciled automatically against user wallet debits.</li>
                    <li>Any discrepancy must be reported within 7 business days from date of issue.</li>
                </ol>
            </div>

            <div class="text-center flex flex-col justify-between items-center h-28">
                <p class="font-extrabold text-gray-800 uppercase tracking-wider text-[11px]">For {{ $platform['legal_name'] }}</p>
                <div class="border-b border-gray-400 w-48 mb-1"></div>
                <div>
                    <p class="font-black text-gray-900 uppercase">Authorized Signatory</p>
                    <p class="text-[9px] text-gray-400 font-mono">(Digitally Verified System Invoice)</p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>

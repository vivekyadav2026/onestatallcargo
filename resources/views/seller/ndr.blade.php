@extends('layouts.seller')
@section('title', 'NDR Management - OneStall Cargo')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Non-Delivery Reports (NDR)</h1>
            <p class="text-sm text-gray-500 mt-1">Take action on failed deliveries to prevent RTO.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-xl"><i class="fa-solid fa-check mr-2"></i> {{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                    <th class="px-6 py-4">AWB & Details</th>
                    <th class="px-6 py-4">Failed Reason</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Action Required</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($ndrShipments as $shipment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900">{{ $shipment->awb_number }}</div>
                        <div class="text-xs text-gray-500">{{ $shipment->receiver_name }} - {{ $shipment->delivery_city }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-red-600 font-bold text-xs"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Customer Unavailable</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-orange-50 text-orange-700">Action Pending</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('seller.ndr.post', $shipment->awb_number) }}" method="POST" class="flex items-center justify-end gap-2">
                            @csrf
                            <select name="ndr_action" required class="border border-gray-200 rounded-lg text-xs px-2 py-1 outline-none focus:border-gray-400">
                                <option value="">- Select Action -</option>
                                <option value="Re-attempt">Re-attempt Tomorrow</option>
                                <option value="Hold">Hold at Hub</option>
                                <option value="RTO">Return to Origin (RTO)</option>
                            </select>
                            <button type="submit" class="bg-black text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-gray-800 transition">Submit</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-check-circle text-4xl mb-3 text-green-300"></i>
                        <p class="font-bold">No NDRs action required!</p>
                        <p class="text-xs mt-1">All your shipments are delivering smoothly.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

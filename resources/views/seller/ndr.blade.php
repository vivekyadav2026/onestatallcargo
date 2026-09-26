@extends('layouts.seller')
@section('title', 'NDR Management - OneStall Cargo')

@section('content')
<div class="space-y-6" x-data="{ activeTab: '{{ $tab ?? 'action_required' }}' }">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">NDR Management</h1>
        </div>
    </div>

    <!-- Main Container -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        
        <!-- Status Tabs (Matches Screenshot 2) -->
        <div class="flex border-b border-gray-100 px-4 pt-2 overflow-x-auto whitespace-nowrap bg-white">
            <button @click="window.location.href='?tab=action_required'" :class="activeTab === 'action_required' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-5 py-3 text-xs transition">Action Required <span class="ml-1 font-extrabold" :class="activeTab === 'action_required' ? 'text-red-500' : 'text-gray-400'">{{ $tab === 'action_required' ? $ndrShipments->total() : '' }}</span></button>
            <button @click="window.location.href='?tab=action_taken'" :class="activeTab === 'action_taken' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-5 py-3 text-xs transition">Action Taken <span class="ml-1 font-extrabold" :class="activeTab === 'action_taken' ? 'text-[#4338ca]' : 'text-gray-400'">{{ $tab === 'action_taken' ? $ndrShipments->total() : '' }}</span></button>
            <button @click="window.location.href='?tab=delivered'" :class="activeTab === 'delivered' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-5 py-3 text-xs transition">Delivered <span class="ml-1 font-extrabold" :class="activeTab === 'delivered' ? 'text-[#4338ca]' : 'text-gray-400'">{{ $tab === 'delivered' ? $ndrShipments->total() : '' }}</span></button>
            <button @click="window.location.href='?tab=rto'" :class="activeTab === 'rto' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-5 py-3 text-xs transition">RTO <span class="ml-1 font-extrabold" :class="activeTab === 'rto' ? 'text-[#4338ca]' : 'text-gray-400'">{{ $tab === 'rto' ? $ndrShipments->total() : '' }}</span></button>
            <button @click="window.location.href='?tab=all'" :class="activeTab === 'all' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-5 py-3 text-xs transition">All <span class="ml-1 font-extrabold" :class="activeTab === 'all' ? 'text-[#4338ca]' : 'text-gray-400'">{{ $tab === 'all' ? $ndrShipments->total() : '' }}</span></button>
        </div>

        <!-- Filters Row (Matches Screenshot 2) -->
        <div class="p-4 bg-[#f8fafc] border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <div class="bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 flex items-center gap-2">
                    <span>19/08/2026 ~ 25/09/2026</span>
                    <i class="fa-solid fa-xmark text-gray-400 cursor-pointer"></i>
                </div>

                <select class="bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 outline-none">
                    <option>NDR Reasons</option>
                    <option>Customer Unavailable</option>
                    <option>Incorrect Address</option>
                    <option>COD Cash Not Ready</option>
                </select>

                <select class="bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 outline-none">
                    <option>Attempts</option>
                    <option>1st Attempt</option>
                    <option>2nd Attempt</option>
                    <option>3rd Attempt</option>
                </select>

                <input type="text" placeholder="AWB number" class="bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 outline-none w-44">

                <button class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 flex items-center gap-1.5 hover:bg-gray-50">
                    <i class="fa-solid fa-sliders text-gray-400"></i> More Filters
                </button>
            </div>

            <div class="flex items-center gap-2">
                <button class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-700 flex items-center gap-1.5 hover:bg-gray-50">
                    <i class="fa-solid fa-download text-gray-400"></i> Download Report <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </button>
                <button class="px-3 py-2 bg-white border border-blue-200 text-[#4338ca] rounded-xl text-xs font-bold flex items-center gap-1.5 hover:bg-blue-50">
                    <i class="fa-solid fa-upload"></i> Bulk NDR CSV Update
                </button>
            </div>
        </div>

        <!-- Table View (Matches Screenshot 2) -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50/80 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <th class="px-4 py-3"><input type="checkbox" class="rounded border-gray-300"></th>
                        <th class="px-4 py-3">NDR RAISED DATE</th>
                        <th class="px-4 py-3">ORDER DETAILS</th>
                        <th class="px-4 py-3">CUSTOMER DETAILS</th>
                        <th class="px-4 py-3">ORDER VALUE & PAYMENT</th>
                        <th class="px-4 py-3">COURIER & AWB</th>
                        <th class="px-4 py-3">ATTEMPTS</th>
                        <th class="px-4 py-3">LATEST NDR REASON</th>
                        <th class="px-4 py-3">STATUS</th>
                        <th class="px-4 py-3 text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium text-gray-700">
                    @forelse($ndrShipments as $shipment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3"><input type="checkbox" class="rounded border-gray-300"></td>
                            <td class="px-4 py-3 font-semibold text-gray-500">{{ $shipment->updated_at->format('d M Y, h:i A') }}</td>
                            <td class="px-4 py-3 font-bold text-gray-900">{{ $shipment->order_id ?? $shipment->awb_number }}</td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-800">{{ $shipment->receiver_name }}</div>
                                <div class="text-[10px] text-gray-400">{{ $shipment->delivery_city }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-900">&#8377; {{ number_format($shipment->invoice_value, 2) }}</div>
                                <span class="text-[9px] font-bold uppercase px-1.5 py-0.5 rounded {{ $shipment->is_cod ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800' }}">{{ $shipment->is_cod ? 'COD' : 'PREPAID' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-blue-600 font-mono">{{ $shipment->awb_number }}</div>
                                <div class="text-[10px] text-gray-500">Delhivery Surface</div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 bg-gray-100 rounded text-gray-800 font-bold">1</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-red-600 font-semibold"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Customer Unavailable</span>
                            </td>
                            <td class="px-4 py-3">
                                @if(is_null($shipment->ndr_action) && $shipment->status === 'NDR')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-amber-50 text-amber-700 border border-amber-200">Action Required</span>
                                @elseif(!is_null($shipment->ndr_action) && $shipment->status === 'NDR')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-blue-50 text-blue-700 border border-blue-200">Action Taken</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-gray-50 text-gray-700 border border-gray-200">{{ $shipment->status }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                @if(is_null($shipment->ndr_action) && $shipment->status === 'NDR')
                                <form action="{{ route('seller.ndr.post', $shipment->awb_number) }}" method="POST" class="flex items-center justify-end gap-1.5">
                                    @csrf
                                    <select name="ndr_action" required class="border border-gray-300 rounded-lg text-xs px-2 py-1 outline-none focus:border-[#4338ca]">
                                        <option value="">Select Action</option>
                                        <option value="Re-attempt">Re-attempt</option>
                                        <option value="RTO">Return (RTO)</option>
                                    </select>
                                    <button type="submit" class="bg-[#4338ca] text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-[#3730a3]">Submit</button>
                                </form>
                                @else
                                <span class="text-xs font-bold text-gray-500">Requested: {{ $shipment->ndr_action ?? 'N/A' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-20 text-center">
                                <p class="text-sm font-bold text-gray-400">No NDR records match the current filters.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($ndrShipments->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-[#f8fafc]">
                {{ $ndrShipments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

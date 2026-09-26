@extends('layouts.seller')
@section('title', 'Weight Discrepancy - OneStall Cargo')

@section('content')
<div class="space-y-6" x-data="{ activeTab: '{{ $tab ?? 'all' }}', showDisputeModal: false, selectedDiscId: null, selectedAwb: '' }">
    
    <!-- Top Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Weight Discrepancy</h1>
            <p class="text-sm text-gray-500 font-medium mt-1">Review weight discrepancies raised by couriers.</p>
        </div>
    </div>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Total Discrepancies</div>
            <div class="text-2xl font-black text-gray-900">{{ $totalDiscrepancies }}</div>
            <div class="text-xs text-gray-400 font-medium mt-2">Overall raised</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Action Required</div>
            <div class="text-2xl font-black text-red-600">{{ $actionRequired }}</div>
            <div class="text-xs text-gray-400 font-medium mt-2">Pending your action</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Total Amount on Hold</div>
            <div class="text-2xl font-black text-amber-500">&#8377;{{ number_format($amountOnHold, 2) }}</div>
            <div class="text-xs text-gray-400 font-medium mt-2">Across pending/disputed AWBs</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Disputes Resolved</div>
            <div class="text-2xl font-black text-gray-900">{{ $resolved }}</div>
            <div class="text-xs text-gray-400 font-medium mt-2">In seller favour</div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        
        <!-- Status Tabs -->
        <div class="flex justify-between items-center border-b border-gray-100 pr-4 bg-white">
            <div class="flex px-4 pt-2 overflow-x-auto whitespace-nowrap">
                <button @click="window.location.href='?tab=all'" :class="activeTab === 'all' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-5 py-3 text-xs transition">All</button>
                <button @click="window.location.href='?tab=action_required'" :class="activeTab === 'action_required' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-5 py-3 text-xs transition">Action Required</button>
                <button @click="window.location.href='?tab=disputed'" :class="activeTab === 'disputed' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-5 py-3 text-xs transition">Disputed</button>
                <button @click="window.location.href='?tab=accepted'" :class="activeTab === 'accepted' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-5 py-3 text-xs transition">Accepted</button>
                <button @click="window.location.href='?tab=closed'" :class="activeTab === 'closed' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-5 py-3 text-xs transition">Closed Disputes</button>
            </div>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50/80 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <th class="px-4 py-3">Discrepancy Raised Date</th>
                        <th class="px-4 py-3">Order ID</th>
                        <th class="px-4 py-3">AWB / Courier</th>
                        <th class="px-4 py-3">Applied Weight</th>
                        <th class="px-4 py-3">Charged Weight</th>
                        <th class="px-4 py-3">Amount On Hold</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium text-gray-700">
                    @forelse($discrepancies as $disc)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-semibold text-gray-500">{{ $disc->created_at->format('d M Y, h:i A') }}</td>
                            <td class="px-4 py-3 font-bold text-gray-900">{{ $disc->shipment->order_id ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-blue-600 font-mono">{{ $disc->shipment->awb_number }}</div>
                            </td>
                            <td class="px-4 py-3">{{ $disc->applied_weight }} kg</td>
                            <td class="px-4 py-3 text-red-600 font-bold">{{ $disc->charged_weight }} kg</td>
                            <td class="px-4 py-3 font-bold text-amber-600">&#8377;{{ number_format($disc->discrepancy_fee, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-gray-100 text-gray-600">{{ $disc->status }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                @if($disc->status === 'pending')
                                    <form action="{{ route('seller.weight.action', $disc->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to ACCEPT this discrepancy? This will deduct the amount from your wallet.')">
                                        @csrf
                                        <input type="hidden" name="action" value="accept">
                                        <button type="submit" class="bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-gray-300 transition shadow-sm">Accept</button>
                                    </form>
                                    <button @click="showDisputeModal = true; selectedDiscId = '{{ $disc->id }}'; selectedAwb = '{{ $disc->shipment->awb_number }}'" class="bg-[#4338ca] text-white px-3 py-1.5 rounded-lg text-xs font-bold ml-1 hover:bg-[#3730a3] transition shadow-sm">Dispute</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-20 text-center">
                                <i class="fa-solid fa-scale-balanced text-4xl text-gray-200 mb-4 block"></i>
                                <p class="text-sm font-bold text-gray-400">No weight discrepancies found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($discrepancies->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-[#f8fafc]">
                {{ $discrepancies->links() }}
            </div>
        @endif
    </div>

    <!-- Improved Dispute Modal -->
    <div x-show="showDisputeModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div x-show="showDisputeModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm transition-opacity" @click="showDisputeModal = false" aria-hidden="true"></div>
            
            <div x-show="showDisputeModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100">
                
                <form :action="'/seller/weight-discrepancies/' + selectedDiscId + '/action'" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="action" value="dispute">
                    <div class="bg-white px-6 pt-6 pb-6">
                        <div class="flex justify-between items-start mb-5">
                            <div>
                                <h3 class="text-[17px] font-extrabold text-gray-900">Dispute Weight Discrepancy</h3>
                                <p class="text-[13px] text-gray-500 mt-0.5">Submit proof for AWB <span x-text="selectedAwb" class="font-bold text-blue-600"></span></p>
                            </div>
                            <button type="button" @click="showDisputeModal = false" class="text-gray-400 hover:text-gray-600 transition bg-gray-50 hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center">
                                <i class="fa-solid fa-times text-sm"></i>
                            </button>
                        </div>
                        
                        <div class="space-y-5">
                            <!-- Remarks -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">Remarks / Reason</label>
                                <textarea name="remarks" required rows="3" placeholder="Explain why the charged weight is incorrect..." class="w-full py-2 px-3 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition shadow-sm placeholder:text-gray-400 resize-none"></textarea>
                            </div>
                            
                            <!-- Proof Images -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">Evidence Image</label>
                                <div class="mt-1 flex justify-center px-6 py-6 border-2 border-dashed border-gray-200 rounded-xl hover:border-blue-400 hover:bg-blue-50/50 transition cursor-pointer relative group">
                                    <input name="evidence_image" required type="file" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white border-t border-gray-100 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-2xl">
                        <button type="submit" class="inline-flex justify-center rounded-lg px-6 py-2.5 bg-[#4338ca] text-sm font-bold text-white hover:bg-[#3730a3] shadow-sm transition items-center gap-2">
                            Submit Dispute
                        </button>
                        <button type="button" @click="showDisputeModal = false" class="inline-flex justify-center rounded-lg border border-gray-200 px-6 py-2.5 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


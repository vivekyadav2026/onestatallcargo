@extends('layouts.admin')
@section('title', 'Weight Discrepancy - Admin')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, selectedImg: '' }">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Weight Discrepancies (Admin)</h1>
            <p class="text-sm text-gray-500 mt-1">Manage and resolve weight disputes across all sellers.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-x-auto">
                        <div class="overflow-x-auto w-full">
<table class="w-full text-left text-sm whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                    <th class="px-6 py-4">AWB & Seller</th>
                    <th class="px-6 py-4">Applied vs Charged</th>
                    <th class="px-6 py-4">Fee / Hold</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Proof</th>
                    <th class="px-6 py-4 text-right">Admin Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-gray-700">
                @forelse($discrepancies as $disc)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900">{{ $disc->shipment->awb_number ?? 'N/A' }}</div>
                        <div class="text-[10px] text-gray-500 mt-1 uppercase">Seller: {{ $disc->user->name ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900">{{ $disc->applied_weight }} kg</div>
                        <div class="text-[10px] text-red-600 font-bold mt-1 uppercase">Charged: {{ $disc->charged_weight }} kg</div>
                    </td>
                    <td class="px-6 py-4 font-bold text-amber-600">
                        &#8377;{{ number_format($disc->discrepancy_fee, 2) }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-gray-100 text-gray-600">{{ $disc->status }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($disc->evidence_image)
                            <button @click="selectedImg = '{{ Storage::url($disc->evidence_image) }}'; showModal = true" class="text-xs text-[#4338ca] font-bold hover:underline"><i class="fa-solid fa-image"></i> View Proof</button>
                        @else
                            <span class="text-xs text-gray-400">N/A</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($disc->status === 'disputed')
                            <form action="{{ route('admin.weight.action', $disc->id) }}" method="POST" class="inline-block">
                                @csrf
                                <input type="hidden" name="action" value="seller_won">
                                <button type="submit" class="bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-green-700 transition shadow-sm" title="Accept Proof (Refund Seller)">Approve</button>
                            </form>
                            <form action="{{ route('admin.weight.action', $disc->id) }}" method="POST" class="inline-block ml-1">
                                @csrf
                                <input type="hidden" name="action" value="courier_won">
                                <button type="submit" class="bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-red-700 transition shadow-sm" title="Reject Proof (Charge Seller)">Reject</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 font-medium">No weight discrepancies found.</td>
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

    <!-- Image Modal -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="showModal = false" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showModal" x-transition class="inline-block align-bottom bg-transparent text-left overflow-hidden transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <div class="relative">
                    <button @click="showModal = false" class="absolute -top-4 -right-4 text-white hover:text-gray-300 text-2xl"><i class="fa-solid fa-times"></i></button>
                    <img :src="selectedImg" class="w-full h-auto rounded-lg shadow-2xl">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


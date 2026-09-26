@extends('layouts.admin')
@section('title', 'Weight Freezes (Admin) - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Weight Freeze Requests</h1>
            <p class="text-sm text-gray-500 mt-1">Review and approve SKU weight freeze requests from sellers.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-x-auto">
                        <div class="overflow-x-auto w-full">
<table class="w-full text-left text-sm whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                    <th class="px-6 py-4">Seller & SKU</th>
                    <th class="px-6 py-4">Dimensions</th>
                    <th class="px-6 py-4">Requested Weight</th>
                    <th class="px-6 py-4">Proof Images</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-gray-700">
                @forelse($freezes as $freeze)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900">{{ $freeze->product_name }}</div>
                        <div class="text-[10px] text-gray-500 mt-1 uppercase">Seller: {{ $freeze->user->name ?? 'N/A' }} | SKU: {{ $freeze->sku ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 font-mono text-sm">
                        {{ $freeze->length }} x {{ $freeze->width }} x {{ $freeze->height }} cm
                    </td>
                    <td class="px-6 py-4 font-bold text-blue-600">
                        {{ $freeze->weight }} kg
                    </td>
                    <td class="px-6 py-4">
                        @if($freeze->packaging_image)
                            @php $images = json_decode($freeze->packaging_image, true); @endphp
                            @if(is_array($images) && count($images) > 0)
                                <a href="{{ Storage::url($images[0]) }}" target="_blank" class="text-xs text-[#4338ca] hover:underline"><i class="fa-solid fa-image"></i> View {{ count($images) }} Proof(s)</a>
                            @else
                                <span class="text-xs text-gray-400">No Proof</span>
                            @endif
                        @else
                            <span class="text-xs text-gray-400">No Proof</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-gray-100 text-gray-600">{{ $freeze->status }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($freeze->status === 'requested')
                            <form action="{{ route('admin.weight.freeze.action', $freeze->id) }}" method="POST" class="inline-block">
                                @csrf
                                <input type="hidden" name="action" value="accepted">
                                <button type="submit" class="bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-green-700 transition shadow-sm">Accept</button>
                            </form>
                            <form action="{{ route('admin.weight.freeze.action', $freeze->id) }}" method="POST" class="inline-block ml-1">
                                @csrf
                                <input type="hidden" name="action" value="rejected">
                                <button type="submit" class="bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-red-700 transition shadow-sm">Reject</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 font-medium">No weight freeze requests found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
</div>
        @if($freezes->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-[#f8fafc]">
                {{ $freezes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection


@extends('layouts.hub')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    
    <!-- Left Column: Create Bag -->
    <div class="md:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <h2 class="font-extrabold text-gray-800"><i class="fa-solid fa-sack-dollar mr-2"></i> Master Manifest / Bag</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('hub.bagging.create') }}" method="POST">
                    @csrf
                    <p class="text-xs text-gray-500 mb-4">Generate a new Master Bag ID before scanning parcels into it for bulk dispatch.</p>
                    <button type="submit" class="w-full py-4 bg-[#1e293b] hover:bg-black text-white font-extrabold rounded-xl shadow transition">
                        Generate New Bag
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-xl text-sm">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-red-700 font-bold rounded-xl text-sm">{{ $errors->first() }}</div>
        @endif
    </div>

    <!-- Right Column: Scan to Bag -->
    <div class="md:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <h2 class="font-extrabold text-gray-800"><i class="fa-solid fa-barcode mr-2"></i> Scan Parcel into Bag</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('hub.bagging.scan') }}" method="POST" class="flex flex-col md:flex-row gap-4">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Select Open Bag</label>
                        <select name="bag_id" required class="w-full text-sm font-bold px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl outline-none focus:border-[#FFD700]">
                            <option value="">-- Choose Bag --</option>
                            @foreach($bags->where('status', 'Open') as $bag)
                                <option value="{{ $bag->id }}">{{ $bag->bag_number }} (Created: {{ $bag->created_at->format('H:i') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Parcel AWB</label>
                        <input type="text" name="awb_number" required autofocus placeholder="OSC..." class="w-full text-lg font-bold uppercase tracking-widest px-4 py-2.5 bg-white border border-gray-300 rounded-xl focus:border-[#FFD700] focus:ring-2 focus:ring-[#FFD700] outline-none transition">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="h-[46px] px-8 bg-[#FFD700] hover:bg-[#E5C100] text-gray-900 font-extrabold rounded-xl shadow transition whitespace-nowrap">
                            Add to Bag
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <h2 class="font-extrabold text-gray-800">Recent Bags</h2>
            </div>
            <div class="overflow-x-auto w-full">
<table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-white text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <th class="px-6 py-3">Bag ID</th>
                        <th class="px-6 py-3">Created</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Parcels Inside</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bags as $bag)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-bold text-gray-900">{{ $bag->bag_number }}</td>
                        <td class="px-6 py-3 text-gray-500 text-xs">{{ $bag->created_at->format('d M, H:i') }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 rounded bg-blue-50 font-bold text-[10px] uppercase tracking-wider text-blue-700">{{ $bag->status }}</span>
                        </td>
                        <td class="px-6 py-3 font-bold text-gray-700 text-center">
                            {{ \App\Models\Shipment::where('bag_id', $bag->id)->count() }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">No bags created yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
</div>
        </div>
    </div>
</div>
@endsection

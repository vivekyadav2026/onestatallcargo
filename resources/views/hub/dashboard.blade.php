@extends('layouts.hub')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    
    <!-- Left Column: Scanner Tool -->
    <div class="md:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden" x-data="{ scanAction: 'Receive' }">
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <h2 class="font-extrabold text-gray-800"><i class="fa-solid fa-barcode mr-2"></i> Scan AWB</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('hub.scan') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">AWB Number</label>
                        <input type="text" name="awb_number" required autofocus class="w-full text-xl font-bold uppercase tracking-widest px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl focus:bg-white focus:border-[#FFD700] focus:ring-2 focus:ring-[#FFD700] outline-none transition" placeholder="OSC...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Action</label>
                        <select name="action" x-model="scanAction" class="w-full font-bold px-4 py-3 bg-white border border-gray-300 rounded-xl outline-none">
                            <option value="Receive">In-Scan (Receive at Hub)</option>
                            <option value="Dispatch">Out-Scan (Hub Dispatch)</option>
                            <option value="Out for Delivery">Assign to Rider (OFD)</option>
                        </select>
                    </div>

                    <!-- Rider Assignment (Only visible on OFD) -->
                    <div x-show="scanAction == 'Out for Delivery'" style="display: none;">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Select Rider</label>
                        <select name="rider_id" class="w-full font-bold px-4 py-3 bg-blue-50 border border-blue-200 text-blue-900 rounded-xl outline-none">
                            <option value="">-- Choose Rider --</option>
                            @foreach($riders as $rider)
                                <option value="{{ $rider->id }}">{{ $rider->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full py-4 bg-[#FFD700] hover:bg-[#D4AF37] text-gray-900 font-extrabold rounded-xl shadow transition">
                        Submit Scan
                    </button>
                </form>
            </div>
        </div>
        
        <div class="bg-[#1e293b] rounded-2xl p-6 text-white text-center">
            <i class="fa-solid fa-camera text-4xl mb-3 text-gray-400"></i>
            <h3 class="font-bold mb-1">Bagging & Evidence</h3>
            <p class="text-xs text-gray-400">Hub cameras are active. Ensure parcels are scanned under the recording zone for damage disputes.</p>
        </div>
    </div>

    <!-- Right Column: Recent Activity Log -->
    <div class="md:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
            <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <h2 class="font-extrabold text-gray-800"><i class="fa-solid fa-list-check mr-2"></i> Recent Scans</h2>
            </div>
            <div class="p-0">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                            <th class="px-6 py-4">AWB</th>
                            <th class="px-6 py-4">Time</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Rider</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentScans as $scan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-bold text-[#D4AF37]">{{ $scan->awb_number }}</td>
                            <td class="px-6 py-3 text-gray-500 text-xs">{{ $scan->updated_at->format('H:i:s d M') }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-1 rounded bg-gray-100 font-bold text-[10px] uppercase tracking-wider text-gray-600">{{ $scan->status }}</span>
                            </td>
                            <td class="px-6 py-3 text-xs text-gray-500">
                                {{ $scan->rider_id ? \App\Models\User::find($scan->rider_id)->name : '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add AlpineJS -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection

@extends('layouts.rider')

@section('content')
<div x-data="{ tab: 'deliveries' }">
    <!-- Tabs -->
    <div class="flex bg-gray-200 p-1 rounded-xl mb-4">
        <button @click="tab = 'deliveries'" :class="tab == 'deliveries' ? 'bg-white shadow text-[#1e293b]' : 'text-gray-500'" class="flex-1 py-2 text-sm font-bold rounded-lg transition">Deliveries</button>
        <button @click="tab = 'pickups'" :class="tab == 'pickups' ? 'bg-white shadow text-[#1e293b]' : 'text-gray-500'" class="flex-1 py-2 text-sm font-bold rounded-lg transition">Pickups</button>
    </div>

    <!-- Deliveries List -->
    <div x-show="tab == 'deliveries'" class="space-y-4">
        @forelse($pendingDeliveries as $del)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 relative overflow-hidden" x-data="{ showUpload: false }">
            <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
            <div class="flex justify-between items-start mb-3">
                <div>
                    <div class="font-extrabold text-gray-900">{{ $del->receiver_name }}</div>
                    <div class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">{{ $del->awb_number }}</div>
                </div>
                <div class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-1 rounded">Delivery</div>
            </div>
            <div class="text-xs text-gray-600 mb-3"><i class="fa-solid fa-location-dot w-4"></i> {{ $del->delivery_address }}, {{ $del->delivery_city }}</div>
            
            @if($del->is_cod)
            <div class="mb-4 bg-yellow-50 border border-yellow-200 text-yellow-800 text-xs font-bold p-2 rounded flex justify-between">
                <span>Collect COD:</span><span>₹{{ number_format($del->total_amount, 2) }}</span>
            </div>
            @endif

            <button @click="showUpload = !showUpload" class="w-full py-3 bg-[#1e293b] text-white text-sm font-bold rounded-xl shadow-md"><i class="fa-solid fa-camera mr-2"></i> Deliver & Upload Proof</button>
            
            <!-- Video Evidence Form -->
            <form x-show="showUpload" action="{{ route('rider.evidence.upload') }}" method="POST" enctype="multipart/form-data" class="mt-4 pt-4 border-t border-gray-100 space-y-3" style="display: none;">
                @csrf
                <input type="hidden" name="awb_number" value="{{ $del->awb_number }}">
                <input type="hidden" name="action_type" value="Delivery">
                
                <div class="bg-gray-50 border border-dashed border-gray-300 p-4 rounded-xl text-center">
                    <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-400 mb-2"></i>
                    <div class="text-xs text-gray-500 font-bold">Tap to record video</div>
                    <input type="file" name="video_file" accept="video/*" capture="environment" class="mt-2 text-xs w-full">
                </div>
                <input type="text" name="otp" placeholder="Enter Customer OTP (Optional)" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none">
                <button type="submit" class="w-full py-3 bg-[#FFD700] text-gray-900 text-sm font-extrabold rounded-xl shadow-md">Complete Delivery</button>
            </form>
        </div>
        @empty
        <div class="text-center p-8 text-gray-400">
            <i class="fa-solid fa-box-open text-4xl mb-2 text-gray-300"></i>
            <p class="font-bold text-sm">No pending deliveries!</p>
        </div>
        @endforelse
    </div>

    <!-- Pickups List -->
    <div x-show="tab == 'pickups'" class="space-y-4" style="display: none;">
        @forelse($pendingPickups as $pickup)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 relative overflow-hidden" x-data="{ showUpload: false }">
            <div class="absolute top-0 left-0 w-1 h-full bg-purple-500"></div>
            <div class="flex justify-between items-start mb-3">
                <div>
                    <div class="font-extrabold text-gray-900">{{ $pickup->user->name ?? 'Seller' }}</div>
                    <div class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">{{ $pickup->awb_number }}</div>
                </div>
                <div class="bg-purple-100 text-purple-700 text-[10px] font-bold px-2 py-1 rounded">Pickup</div>
            </div>
            
            <button @click="showUpload = !showUpload" class="w-full py-3 bg-[#1e293b] text-white text-sm font-bold rounded-xl shadow-md"><i class="fa-solid fa-camera mr-2"></i> Pickup & Upload Proof</button>
            
            <form x-show="showUpload" action="{{ route('rider.evidence.upload') }}" method="POST" enctype="multipart/form-data" class="mt-4 pt-4 border-t border-gray-100 space-y-3" style="display: none;">
                @csrf
                <input type="hidden" name="awb_number" value="{{ $pickup->awb_number }}">
                <input type="hidden" name="action_type" value="Pickup">
                
                <div class="bg-gray-50 border border-dashed border-gray-300 p-4 rounded-xl text-center">
                    <i class="fa-solid fa-video text-2xl text-gray-400 mb-2"></i>
                    <div class="text-xs text-gray-500 font-bold">Record parcel condition</div>
                    <input type="file" name="video_file" accept="video/*" capture="environment" class="mt-2 text-xs w-full">
                </div>
                <button type="submit" class="w-full py-3 bg-[#FFD700] text-gray-900 text-sm font-extrabold rounded-xl shadow-md">Complete Pickup</button>
            </form>
        </div>
        @empty
        <div class="text-center p-8 text-gray-400">
            <i class="fa-solid fa-truck-ramp-box text-4xl mb-2 text-gray-300"></i>
            <p class="font-bold text-sm">No pending pickups!</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

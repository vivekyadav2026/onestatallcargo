@extends('layouts.admin')
@section('title', 'Video Evidence - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Evidence Database</h1>
            <p class="text-sm text-gray-500 mt-1">Live video/photo proof uploaded by Delivery Riders.</p>
        </div>
        
        <!-- Search Form -->
        <form action="{{ route('admin.evidence.index') }}" method="GET" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by AWB..." class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[var(--gold)] w-64">
            <button type="submit" class="px-4 py-2 bg-gray-900 text-white font-bold rounded-xl text-sm hover:bg-black transition"><i class="fa-solid fa-search"></i></button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($evidences as $shipment)
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
            <!-- Video Player -->
            <div class="bg-black aspect-video relative">
                @if(Str::endsWith($shipment->video_evidence_url, ['.mp4', '.mov', '.avi']))
                    <video src="{{ $shipment->video_evidence_url }}" controls class="w-full h-full object-cover"></video>
                @else
                    <img src="{{ $shipment->video_evidence_url }}" alt="Evidence" class="w-full h-full object-cover">
                @endif
                <div class="absolute top-3 right-3 px-2 py-1 bg-black/60 text-white text-[10px] font-bold rounded-lg uppercase tracking-wider backdrop-blur-sm">
                    {{ $shipment->status == 'Delivered' ? 'Delivery Proof' : 'Pickup Proof' }}
                </div>
            </div>
            
            <div class="p-5 flex-1 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-gray-900 text-lg">{{ $shipment->awb_number }}</h3>
                        <span class="text-xs font-bold text-gray-500">{{ $shipment->updated_at->format('d M, H:i') }}</span>
                    </div>
                    <div class="text-sm text-gray-600 mb-1"><i class="fa-solid fa-location-dot w-4 text-gray-400"></i> {{ $shipment->delivery_city }}, {{ $shipment->delivery_pincode }}</div>
                    <div class="text-sm text-gray-600"><i class="fa-solid fa-motorcycle w-4 text-gray-400"></i> Rider: <span class="font-bold text-gray-800">{{ $shipment->assignedRider->name ?? 'Unknown Rider' }}</span></div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                    <a href="{{ $shipment->video_evidence_url }}" download="evidence_{{ $shipment->awb_number }}" class="text-xs font-bold text-blue-600 hover:text-blue-800"><i class="fa-solid fa-download mr-1"></i> Download</a>
                    
                    <a href="https://wa.me/?text=Here%20is%20the%20delivery%20evidence%20for%20AWB%20{{ $shipment->awb_number }}:%20{{ url($shipment->video_evidence_url) }}" target="_blank" class="text-xs font-bold text-green-600 hover:text-green-800"><i class="fa-brands fa-whatsapp mr-1"></i> Share</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-1 md:col-span-2 lg:col-span-3 py-12 text-center text-gray-400 bg-white rounded-3xl border border-gray-200">
            <i class="fa-solid fa-video-slash text-4xl mb-3"></i>
            <p class="font-bold">No evidence files found.</p>
            <p class="text-sm mt-1">Try clearing your search filters or wait for Riders to upload new videos.</p>
        </div>
        @endforelse
    </div>
    
    <div class="mt-6">
        {{ $evidences->links() }}
    </div>
</div>
@endsection

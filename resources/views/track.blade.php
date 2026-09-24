@extends('layouts.public')
@section('title', 'Track Shipment | OneStall Cargo')

@section('content')
<section class="py-10 bg-gray-50 min-h-[60vh]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-brand-navy px-8 py-10 text-center">
                <h1 class="text-3xl font-extrabold text-white mb-2">Track Your Shipment</h1>
                <p class="text-gray-300">Enter your AWB or Shipment ID below</p>
            </div>
            
            <div class="p-8">
                <form action="{{ route('track.post') }}" method="POST" class="mb-8">
                    @csrf
                    <div class="flex flex-col sm:flex-row gap-4">
                        <input type="text" name="awb" placeholder="e.g. OSC12345678" class="flex-grow px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-transparent text-lg font-bold" required>
                        <button type="submit" class="px-8 py-3 rounded-lg bg-brand-blue text-white font-bold text-lg hover:bg-brand-navy transition">Track</button>
                    </div>
                </form>

                @if(isset($shipment))
                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <p class="text-sm text-gray-500 font-bold uppercase">AWB Number</p>
                                <h2 class="text-2xl font-black text-brand-navy">{{ $shipment->awb_number }}</h2>
                            </div>
                            <span class="px-3 py-1 bg-green-100 text-green-700 font-bold uppercase rounded text-sm">{{ $shipment->status }}</span>
                        </div>
                        
                        <div class="relative pl-6 border-l-2 border-brand-yellow space-y-8 mt-8">
                            <div class="relative">
                                <div class="absolute -left-[31px] w-4 h-4 bg-brand-yellow rounded-full border-4 border-white"></div>
                                <p class="text-xs font-bold text-gray-400">{{ $shipment->created_at->format('d M Y, h:i A') }}</p>
                                <p class="text-base font-bold text-gray-900">Shipment Booked</p>
                                <p class="text-sm text-gray-600">Information received.</p>
                            </div>
                        </div>
                    </div>
                @elseif(request()->isMethod('post'))
                    <div class="mt-8 border-t border-gray-200 pt-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-500 mb-4 text-2xl"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Shipment Not Found</h3>
                        <p class="text-gray-600">We couldn't find a shipment with that AWB. Please check the number and try again.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
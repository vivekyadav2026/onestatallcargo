@extends('layouts.app')
@section('title', 'Services - OneStall Cargo')

@section('content')
<div class="bg-[#1e293b] py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-extrabold text-white tracking-tight sm:text-5xl">Our Logistics Services</h1>
        <p class="mt-4 text-xl text-gray-300 max-w-2xl mx-auto">From a 500-gram envelope to a 5-ton truckload, we have the network to deliver it.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($services as $service)
        <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm flex flex-col items-start">
            @if($service->icon)
            <div class="w-16 h-16 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-3xl mb-6"><i class="{{ $service->icon }}"></i></div>
            @endif
            <h2 class="text-2xl font-extrabold text-gray-900 mb-3">{{ $service->title }}</h2>
            <p class="text-gray-600 mb-6 flex-1">{{ $service->description }}</p>
        </div>
        @endforeach
    </div>
</div>
</div>
@endsection


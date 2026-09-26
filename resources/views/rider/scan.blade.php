@extends('layouts.rider')
@section('content')
<div class="space-y-6">
    <div class="bg-black text-white p-6 rounded-3xl text-center relative overflow-hidden h-64 flex flex-col justify-center shadow-lg border border-gray-800">
        <i class="fa-solid fa-qrcode text-6xl text-gray-700 animate-pulse mb-4"></i>
        <h2 class="text-xl font-bold">Scanning Area</h2>
        <p class="text-xs text-gray-400 mt-2">Align the barcode within the frame</p>
        
        <div class="absolute inset-0 border-4 border-yellow-500 m-8 rounded-2xl opacity-50"></div>
        <div class="absolute w-full h-1 bg-green-500 opacity-70 top-1/2 left-0" style="animation: scan 2s infinite alternate;"></div>
    </div>
    
    <div class="bg-white p-4 rounded-3xl shadow-sm border border-gray-200">
        <h3 class="font-bold text-gray-900 mb-2">Manual Entry</h3>
        <div class="flex gap-2">
            <input type="text" placeholder="Enter AWB Number" class="flex-1 bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl outline-none text-sm font-bold focus:border-blue-500">
            <button class="bg-[#1e293b] text-white px-5 rounded-xl font-bold hover:bg-black"><i class="fa-solid fa-search"></i></button>
        </div>
    </div>
</div>
<style>@keyframes scan { from { top: 10%; } to { top: 90%; } }</style>
@endsection

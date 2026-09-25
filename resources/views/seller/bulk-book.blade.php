@extends('layouts.seller')
@section('title', 'Bulk Booking - OneStall Cargo')
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Bulk Upload Bookings</h1>
        <p class="text-sm text-gray-500 mt-1">Upload an Excel or CSV file to book 100+ orders instantly via our Carrier Aggregator API.</p>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 md:p-12 text-center">
        <form action="{{ route('seller.bulk.post') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <i class="fa-solid fa-file-csv text-6xl text-gray-300 mb-4"></i>
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Select CSV File</label>
                <input type="file" name="bulk_file" accept=".csv" required class="block w-full max-w-sm mx-auto text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-[#E8027D] file:text-white hover:file:bg-[#d60070] transition">
            </div>

            <div class="bg-blue-50 text-blue-800 p-4 rounded-xl text-sm font-bold max-w-md mx-auto text-left">
                <i class="fa-solid fa-info-circle mr-1"></i> Ensure your CSV has the following headers:
                <ul class="list-disc pl-5 mt-2 font-normal text-xs text-blue-700">
                    <li>Receiver Name, Phone, Address, City, Pincode</li>
                    <li>Weight, Length, Width, Height</li>
                    <li>Payment Type (COD/Prepaid), Invoice Value</li>
                </ul>
            </div>

            <button type="submit" class="px-8 py-3 rounded-xl text-sm font-bold bg-[#1e293b] text-white shadow-md hover:bg-black transition-colors">
                <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Process Bulk Upload
            </button>
        </form>
    </div>
</div>
@endsection



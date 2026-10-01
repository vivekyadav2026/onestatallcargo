@extends('layouts.seller')
@section('title', 'Early COD')

@section('content')
<div class="px-4 md:px-8 py-6 max-w-7xl mx-auto font-sans">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Early COD</h1>
        <div class="text-sm font-medium text-gray-500">
            <a href="{{ route('seller.dashboard') }}" class="hover:text-[var(--gold)] transition">Dashboard</a> / <span class="text-gray-900">Early COD</span>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-bold flex items-center shadow-sm">
        <i class="fa-solid fa-circle-check mr-2 text-xl"></i> {{ session('success') }}
    </div>
    @endif

    <!-- Main Container -->
    <div class="bg-white rounded-[20px] shadow-sm border border-gray-200 p-8 md:p-12">
        
        <!-- Banner Text -->
        <div class="text-center mb-12">
            <h2 class="text-[22px] md:text-[26px] font-extrabold text-gray-800 mb-2 leading-tight">
                <span class="bg-gray-200/60 px-2 rounded">Introducing Early COD - Grow Faster with Daily COD Remittance</span>
            </h2>
            <p class="text-base md:text-lg text-gray-700 font-semibold">
                <span class="bg-gray-200/60 px-2 rounded">Activate Early COD today by selecting your preferred plan</span>
            </p>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Plan 1 -->
            <div class="bg-[#f2fdfd] rounded-[24px] border border-cyan-100 flex flex-col items-center p-6 pb-8 shadow-sm relative pt-12 text-center transition hover:shadow-md">
                <div class="absolute top-0 left-1/2 transform -translate-x-1/2 bg-[#5d16c5] text-white text-[11px] font-bold px-5 py-1.5 rounded-b-xl tracking-wide whitespace-nowrap">
                    20 Payouts/Month
                </div>
                <div class="w-[72px] h-[72px] bg-white rounded-full border-2 border-cyan-100 flex items-center justify-center mb-5 shadow-sm">
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135706.png" class="w-10 h-10 opacity-90" alt="Cash">
                </div>
                <h3 class="text-[22px] font-extrabold text-[#0ea5e9] mb-4 leading-tight">Delivered + 1<br>Day</h3>
                <p class="text-[13px] font-bold text-gray-900 mb-1 leading-snug">At Minimal Transaction<br>Charge</p>
                <p class="text-[14px] text-gray-700 font-medium mb-1">2.00% Of COD amount</p>
                <p class="text-[11px] font-bold text-gray-900 mb-6">(Inclusive GST)</p>
                
                @if(\Auth::user()->early_cod_plan === 'early_t1')
                    <button disabled class="w-full py-2.5 bg-gray-400 text-white text-sm font-bold rounded-lg cursor-not-allowed">Activated</button>
                @else
                    <form action="{{ route('seller.early-cod.activate') }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="plan" value="early_t1">
                        <button type="submit" class="w-full py-2.5 bg-[#8bc34a] hover:bg-[#7cb342] text-white text-sm font-bold rounded-lg transition shadow-sm">Activate</button>
                    </form>
                @endif
            </div>

            <!-- Plan 2 -->
            <div class="bg-[#f2fdfd] rounded-[24px] border border-cyan-100 flex flex-col items-center p-6 pb-8 shadow-sm relative pt-12 text-center transition hover:shadow-md">
                <div class="absolute top-0 left-1/2 transform -translate-x-1/2 bg-[#5d16c5] text-white text-[11px] font-bold px-5 py-1.5 rounded-b-xl tracking-wide whitespace-nowrap">
                    20 Payouts/Month
                </div>
                <div class="w-[72px] h-[72px] bg-white rounded-full border-2 border-cyan-100 flex items-center justify-center mb-5 shadow-sm">
                    <img src="https://cdn-icons-png.flaticon.com/512/2822/2822238.png" class="w-10 h-10 opacity-90" alt="Cash">
                </div>
                <h3 class="text-[22px] font-extrabold text-[#0ea5e9] mb-4 leading-tight">Delivered + 2<br>Days</h3>
                <p class="text-[13px] font-bold text-gray-900 mb-1 leading-snug">At Minimal Transaction<br>Charge</p>
                <p class="text-[14px] text-gray-700 font-medium mb-1">1.75% Of COD amount</p>
                <p class="text-[11px] font-bold text-gray-900 mb-6">(Inclusive GST)</p>
                
                @if(\Auth::user()->early_cod_plan === 'early_t2')
                    <button disabled class="w-full py-2.5 bg-gray-400 text-white text-sm font-bold rounded-lg cursor-not-allowed">Activated</button>
                @else
                    <form action="{{ route('seller.early-cod.activate') }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="plan" value="early_t2">
                        <button type="submit" class="w-full py-2.5 bg-[#8bc34a] hover:bg-[#7cb342] text-white text-sm font-bold rounded-lg transition shadow-sm">Activate</button>
                    </form>
                @endif
            </div>

            <!-- Plan 3 -->
            <div class="bg-[#f2fdfd] rounded-[24px] border border-cyan-100 flex flex-col items-center p-6 pb-8 shadow-sm relative pt-12 text-center transition hover:shadow-md">
                <div class="absolute top-0 left-1/2 transform -translate-x-1/2 bg-[#5d16c5] text-white text-[11px] font-bold px-5 py-1.5 rounded-b-xl tracking-wide whitespace-nowrap">
                    8 Payouts/Month
                </div>
                <div class="w-[72px] h-[72px] bg-white rounded-full border-2 border-cyan-100 flex items-center justify-center mb-5 shadow-sm">
                    <img src="https://cdn-icons-png.flaticon.com/512/2500/2500854.png" class="w-10 h-10 opacity-90" alt="Cash">
                </div>
                <h3 class="text-[22px] font-extrabold text-[#0ea5e9] mb-4 leading-tight">Delivered + 3<br>Days</h3>
                <p class="text-[13px] font-bold text-gray-900 mb-1 leading-snug">At Minimal Transaction<br>Charge</p>
                <p class="text-[14px] text-gray-700 font-medium mb-1">1.00% Of COD amount</p>
                <p class="text-[11px] font-bold text-gray-900 mb-6">(Inclusive GST)</p>
                
                @if(\Auth::user()->early_cod_plan === 'early_t3')
                    <button disabled class="w-full py-2.5 bg-gray-400 text-white text-sm font-bold rounded-lg cursor-not-allowed">Activated</button>
                @else
                    <form action="{{ route('seller.early-cod.activate') }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="plan" value="early_t3">
                        <button type="submit" class="w-full py-2.5 bg-[#8bc34a] hover:bg-[#7cb342] text-white text-sm font-bold rounded-lg transition shadow-sm">Activate</button>
                    </form>
                @endif
            </div>

            <!-- Plan 4 -->
            <div class="bg-[#f2fdfd] rounded-[24px] border border-cyan-100 flex flex-col items-center p-6 pb-8 shadow-sm relative pt-12 text-center transition hover:shadow-md">
                <div class="absolute top-0 left-1/2 transform -translate-x-1/2 bg-[#5d16c5] text-white text-[11px] font-bold px-5 py-1.5 rounded-b-xl tracking-wide whitespace-nowrap">
                    8 Payouts/Month
                </div>
                <div class="w-[72px] h-[72px] bg-white rounded-full border-2 border-cyan-100 flex items-center justify-center mb-5 shadow-sm">
                    <img src="https://cdn-icons-png.flaticon.com/512/2933/2933116.png" class="w-10 h-10 opacity-90" alt="Cash">
                </div>
                <h3 class="text-[22px] font-extrabold text-[#0ea5e9] mb-4 leading-tight">Delivered + 4<br>Days</h3>
                <p class="text-[13px] font-bold text-gray-900 mb-1 leading-snug">At Minimal Transaction<br>Charge</p>
                <p class="text-[14px] text-gray-700 font-medium mb-1">.75% Of COD amount</p>
                <p class="text-[11px] font-bold text-gray-900 mb-6">(Inclusive GST)</p>
                
                @if(\Auth::user()->early_cod_plan === 'early_t4')
                    <button disabled class="w-full py-2.5 bg-gray-400 text-white text-sm font-bold rounded-lg cursor-not-allowed">Activated</button>
                @else
                    <form action="{{ route('seller.early-cod.activate') }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="plan" value="early_t4">
                        <button type="submit" class="w-full py-2.5 bg-[#8bc34a] hover:bg-[#7cb342] text-white text-sm font-bold rounded-lg transition shadow-sm">Activate</button>
                    </form>
                @endif
            </div>

        </div>

        <!-- Footer Text -->
        <div class="mt-16 text-center max-w-3xl mx-auto">
            <h3 class="text-[20px] font-extrabold text-gray-900 mb-3">Why should you Activate Early COD?</h3>
            <p class="text-[13px] text-gray-700 leading-relaxed mb-6 font-medium">
                Get guaranteed remittance in just 2<span class="text-red-500">*</span> days from the shipment delivered date. Grow your business by removing cash flow restrictions. Get full control over your remittance cycle and take better decisions for your business.
            </p>
            <p class="text-[13px] text-gray-600">
                For any queries drop a mail to: <a href="mailto:cs@onestatallcargo.com" class="text-blue-500 hover:underline">cs@onestatallcargo.com</a>
            </p>
        </div>

    </div>
</div>
@endsection
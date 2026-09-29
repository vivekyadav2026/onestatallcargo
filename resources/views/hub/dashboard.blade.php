@extends('layouts.hub')

@section('content')

@php
    $kycDone = $kyc && $kyc->status === 'approved';
    $kycPending = $kyc && $kyc->status === 'pending';
@endphp

<div x-data="{ showKycModal: {{ $errors->any() ? 'true' : 'false' }} }" class="space-y-6">

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 font-bold rounded-xl border border-green-200 text-sm">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-50 text-red-700 font-bold rounded-xl border border-red-200 text-sm mb-6">
            <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ session('error') }}
        </div>
    @endif

    @if($franchise && $franchise->status !== 'approved')
    <!-- Franchise Pending Banner -->
    <div class="bg-gradient-to-r from-red-600 to-red-500 rounded-2xl p-6 text-white shadow-md flex items-center justify-between gap-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-lock text-2xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold">Application Pending Approval</h2>
                <p class="text-sm text-red-100 mt-1">Your franchise application is currently being reviewed by our Admin team. You cannot operate the hub until it is approved.</p>
            </div>
        </div>
    </div>
    @endif

    @if(!$kycDone)
    <!-- Franchise KYC Banner -->
    <div class="bg-gradient-to-r {{ $kycPending ? 'from-yellow-600 to-yellow-500' : 'from-[#1e293b] to-black' }} rounded-2xl p-6 text-white shadow-md flex flex-col md:flex-row items-center justify-between gap-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                <i class="fa-solid {{ $kycPending ? 'fa-clock' : 'fa-id-card' }} text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-extrabold">{{ $kycPending ? 'KYC Under Review' : 'Complete Your Franchise KYC' }}</h3>
                <p class="text-sm opacity-90 mt-1">
                    {{ $kycPending ? 'Your verification documents have been submitted to the Admin and are pending review.' : 'To activate full hub functionalities and settlements, please verify your Franchise details.' }}
                </p>
            </div>
        </div>
        @if(!$kycPending)
            <button @click="showKycModal = true" class="whitespace-nowrap px-6 py-3 bg-[#FFD700] hover:bg-yellow-500 text-gray-900 font-bold rounded-xl shadow-sm transition">
                Submit Documents
            </button>
        @endif
    </div>
    @endif

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
            <div class="p-0 overflow-x-auto w-full">
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
                                {{ $scan->rider_id ? optional(\App\Models\User::find($scan->rider_id))->name ?? 'Deleted Rider' : '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- KYC Modal -->
<div x-show="showKycModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
        <div x-show="showKycModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm transition-opacity" @click="showKycModal = false" aria-hidden="true"></div>
        <div x-show="showKycModal" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <form action="{{ route('hub.kyc.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="bg-white px-6 pt-6 pb-6">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-lg font-extrabold text-gray-900" id="modal-title">Franchise KYC Submission</h3>
                        <button type="button" @click="showKycModal = false" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center transition"><i class="fa-solid fa-times"></i></button>
                    </div>
                    @if($errors->any()) <div class='mb-4 p-3 bg-red-50 text-red-700 text-xs font-bold rounded-lg border border-red-200'><ul class='list-disc pl-4'>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif <div class='space-y-4'>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Business Type</label>
                                <select name="business_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none">
                                    <option value="Proprietorship">Proprietorship</option>
                                    <option value="Partnership">Partnership</option>
                                    <option value="Private Limited">Private Limited</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Document Type</label>
                                <select name="document_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none">
                                    <option value="Aadhaar">Aadhaar</option>
                                    <option value="Voter ID">Voter ID</option>
                                    <option value="Passport">Passport</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Document Number</label>
                                <input type="text" name="document_number" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">PAN Number</label>
                                <input type="text" name="pan_number" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none uppercase">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">ID Front Image</label>
                                <input type="file" name="id_front" accept="image/*,.pdf" class="w-full px-2 py-1 text-xs border border-gray-300 rounded-lg outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">PAN Card Image</label>
                                <input type="file" name="pan_doc" accept="image/*,.pdf" class="w-full px-2 py-1 text-xs border border-gray-300 rounded-lg outline-none">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" @click="showKycModal = false" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg text-sm">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-black text-white font-bold rounded-lg text-sm">Submit KYC</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<!-- Add AlpineJS -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection

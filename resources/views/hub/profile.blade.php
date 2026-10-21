@extends('layouts.hub')

@section('content')
@php
    $warehouses = \App\Models\Warehouse::where('user_id', Auth::id())->orderBy('is_default', 'desc')->orderBy('id', 'desc')->get();
@endphp

<div class="space-y-6 max-w-5xl mx-auto" x-data="hubProfileData()">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900">Manage Account & Settings</h2>
            <p class="text-xs text-gray-500 mt-1">Configure your profile details and pickup warehouse locations</p>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex items-center gap-2 bg-gray-200/80 p-1 rounded-xl">
            <button type="button" @click="tab = 'profile'" :class="tab === 'profile' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'" class="px-4 py-2 rounded-lg text-xs font-bold transition">
                <i class="fa-solid fa-user mr-1.5"></i> Profile
            </button>
            <button type="button" @click="tab = 'warehouses'" :class="tab === 'warehouses' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'" class="px-4 py-2 rounded-lg text-xs font-bold transition">
                <i class="fa-solid fa-warehouse mr-1.5"></i> Pickup Warehouses ({{ $warehouses->count() }})
            </button>
            <button type="button" @click="tab = 'kyc'" :class="tab === 'kyc' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'" class="px-4 py-2 rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                <i class="fa-solid fa-id-card mr-1"></i> KYC Verification
                @if(isset($kyc) && $kyc && $kyc->status === 'approved')
                    <span class="px-1.5 py-0.5 bg-emerald-100 text-emerald-700 text-[9px] font-black uppercase rounded">Approved</span>
                @elseif(isset($kyc) && $kyc && $kyc->status === 'pending')
                    <span class="px-1.5 py-0.5 bg-amber-100 text-amber-700 text-[9px] font-black uppercase rounded">Review</span>
                @else
                    <span class="px-1.5 py-0.5 bg-red-100 text-red-700 text-[9px] font-black uppercase rounded">Pending</span>
                @endif
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 font-bold rounded-xl border border-green-200">
            <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
        </div>
    @endif
    
    @if(isset($errors) && $errors->any())
        <div class="p-4 bg-red-50 text-red-700 font-bold rounded-xl border border-red-200">
            @foreach($errors->all() as $error)
                <p><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- TAB 1: Profile & Security -->
    <div x-show="tab === 'profile'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Left Side: Basic Info Form -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-800 mb-6 border-b border-gray-100 pb-2">Personal Details</h3>
                
                <form action="{{ route('hub.profile.update') }}" method="POST" class="space-y-5" enctype="multipart/form-data">
                    <div class="flex items-center gap-4 mb-4">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="w-16 h-16 rounded-full object-cover">
                        @else
                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400"><i class="fa-solid fa-user text-2xl"></i></div>
                        @endif
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Profile Photo</label>
                            <input type="file" name="avatar" accept="image/*" class="text-xs">
                        </div>
                    </div>
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Email Address</label>
                            <input type="email" value="{{ $user->email }}" disabled class="w-full px-4 py-2 border border-gray-200 bg-gray-50 text-gray-500 rounded-xl outline-none cursor-not-allowed">
                            <p class="text-[10px] text-gray-400 mt-1">Email cannot be changed.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-4">Security</h3>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">New Password</label>
                            <input type="password" name="password" placeholder="Leave blank to keep current password" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white font-bold rounded-xl shadow hover:bg-black transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Side: Franchise / Hub Metadata -->
        <div class="md:col-span-1 space-y-6">
            @if($franchise)
            <div class="bg-gray-900 text-white rounded-2xl shadow-sm p-6 relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-[var(--gold)] mb-4">Franchise Account</h3>
                    
                    <div class="mb-3">
                        <div class="text-[10px] text-gray-400">Company Name</div>
                        <div class="font-bold">{{ $franchise->user->company_name ?? $user->name }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-[10px] text-gray-400">City</div>
                        <div class="font-bold">{{ $franchise->city ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-gray-400">Status</div>
                        <span class="inline-block mt-1 px-2 py-1 rounded-lg text-xs font-bold {{ $franchise->status === 'approved' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                            {{ strtoupper($franchise->status) }}
                        </span>
                    </div>
                </div>
                <i class="fa-solid fa-building absolute -bottom-6 -right-6 text-8xl text-white opacity-5"></i>
            </div>
            @endif

            @if($hub)
            <div class="bg-white border border-[var(--gold)] rounded-2xl shadow-sm p-6 relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-[var(--gold-deep)] mb-4">Hub Management</h3>
                    
                    <div class="mb-3">
                        <div class="text-[10px] text-gray-500">Assigned Hub</div>
                        <div class="font-bold text-gray-900">{{ $hub->name }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-[10px] text-gray-500">Hub Code</div>
                        <div class="font-bold text-gray-900">{{ $hub->hub_code }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-gray-500">Service Area (Pincode)</div>
                        <div class="font-bold text-gray-900">{{ $hub->pincode ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- TAB 2: Pickup Warehouses / Addresses -->
    <div x-show="tab === 'warehouses'" style="display: none;">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-100 pb-4">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Pickup Warehouses & Addresses</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Manage locations where couriers will pick up shipments booked from your hub</p>
                </div>
                <button type="button" @click="openWhModal()" class="px-4 py-2 bg-[#1e1b4b] hover:bg-indigo-950 text-white text-xs font-bold rounded-xl shadow-sm flex items-center gap-2 transition">
                    <i class="fa-solid fa-plus"></i> Add New Warehouse
                </button>
            </div>

            <!-- Warehouse Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($warehouses as $wh)
                    <div class="border rounded-2xl p-5 relative transition hover:border-[#1e1b4b] {{ $wh->is_default ? 'border-[#1e1b4b] bg-indigo-50/20 shadow-sm' : 'border-gray-200 bg-white' }}">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-[#1e1b4b] flex items-center justify-center font-bold text-xs">
                                    <i class="fa-solid fa-warehouse"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $wh->name }}</h4>
                                    <span class="text-[11px] text-gray-500">{{ $wh->city }}, {{ $wh->state }} - {{ $wh->pincode }}</span>
                                </div>
                            </div>
                            @if($wh->is_default)
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-full">Default</span>
                            @else
                                <button type="button" @click="setDefaultWh({{ $wh->id }})" class="text-[10px] text-indigo-600 hover:underline font-semibold">Make Default</button>
                            @endif
                        </div>

                        <div class="space-y-1 text-xs text-gray-600 mb-4 bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-user text-gray-400 w-3.5"></i>
                                <span class="font-medium text-gray-800">{{ $wh->contact_person }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-phone text-gray-400 w-3.5"></i>
                                <span class="font-medium text-gray-800">{{ $wh->phone }}</span>
                            </div>
                            <div class="flex items-start gap-2 pt-1 border-t border-gray-200/60 mt-1">
                                <i class="fa-solid fa-location-dot text-gray-400 w-3.5 mt-0.5"></i>
                                <span class="text-gray-600 leading-relaxed">{{ $wh->address }}</span>
                            </div>
                        </div>

                        <div class="flex justify-end items-center gap-2 pt-2 border-t border-gray-100">
                            <button type="button" @click="openWhModal({{ json_encode($wh) }})" class="px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-100 rounded-lg font-bold flex items-center gap-1.5 transition">
                                <i class="fa-solid fa-pen text-[10px]"></i> Edit
                            </button>
                            <button type="button" @click="deleteWh({{ $wh->id }})" class="px-3 py-1.5 text-xs text-red-600 hover:bg-red-50 rounded-lg font-bold flex items-center gap-1.5 transition">
                                <i class="fa-solid fa-trash text-[10px]"></i> Delete
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-12 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                        <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-warehouse text-xl"></i>
                        </div>
                        <h4 class="font-bold text-gray-900 text-sm">No pickup warehouses yet</h4>
                        <p class="text-xs text-gray-500 mt-1 mb-4">Add your first pickup address to start shipping from your hub</p>
                        <button type="button" @click="openWhModal()" class="px-4 py-2 bg-[#1e1b4b] hover:bg-indigo-950 text-white text-xs font-bold rounded-xl shadow-sm inline-flex items-center gap-2 transition">
                            <i class="fa-solid fa-plus"></i> Add Pickup Warehouse
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal: Add / Edit Warehouse -->
    <div x-show="showWhModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4" x-transition.opacity>
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-gray-100" @click.away="showWhModal = false">
            <div class="px-6 py-4 bg-[#1e1b4b] text-white flex justify-between items-center">
                <h3 class="font-bold text-sm" x-text="whForm.id ? 'Edit Pickup Warehouse' : 'Add New Pickup Warehouse'"></h3>
                <button type="button" @click="showWhModal = false" class="text-gray-300 hover:text-white">
                    <i class="fa-solid fa-xmark fa-lg"></i>
                </button>
            </div>

            <form @submit.prevent="saveWarehouse()" class="p-6 space-y-4">
                <div x-show="whError" class="p-3 bg-red-50 text-red-700 text-xs font-semibold rounded-xl border border-red-200" x-text="whError"></div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Warehouse Name <span class="text-red-500">*</span></label>
                    <input type="text" x-model="whForm.name" placeholder="e.g. Primary Hub Warehouse" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-xs outline-none focus:border-indigo-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Contact Person <span class="text-red-500">*</span></label>
                        <input type="text" x-model="whForm.contact_person" placeholder="Manager Name" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-xs outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Mobile Number (10 Digits) <span class="text-red-500">*</span></label>
                        <input type="tel" x-model="whForm.phone" placeholder="9876543210" maxlength="10" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-xs outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Full Address <span class="text-red-500">*</span></label>
                    <textarea x-model="whForm.address" rows="3" placeholder="Plot No, Street, Landmark" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-xs outline-none focus:border-indigo-500 resize-none"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pincode (6 Digits) <span class="text-red-500">*</span></label>
                        <input type="text" x-model="whForm.pincode" @input="fetchWhPincode()" maxlength="6" placeholder="110001" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-xs outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                        <input type="text" x-model="whForm.city" placeholder="City" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-xs outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">State <span class="text-red-500">*</span></label>
                        <input type="text" x-model="whForm.state" placeholder="State" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-xs outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div class="flex justify-end items-center gap-3 pt-4 border-t border-gray-100">
                    <button type="button" @click="showWhModal = false" class="px-4 py-2 border border-gray-300 rounded-xl text-xs font-bold text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" :disabled="whLoading" class="px-5 py-2 bg-[#1e1b4b] hover:bg-indigo-950 text-white rounded-xl text-xs font-bold shadow transition flex items-center gap-2 disabled:opacity-50">
                        <span x-show="whLoading"><i class="fa-solid fa-spinner fa-spin"></i></span>
                        <span x-text="whForm.id ? 'Update Warehouse' : 'Save Warehouse'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 3: KYC Verification -->
    <div x-show="tab === 'kyc'" style="display: none;" class="space-y-6">
        
        @if(isset($kyc) && $kyc && $kyc->status === 'approved')
            <div class="bg-green-50/80 border border-green-200 rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 text-green-700 rounded-xl flex items-center justify-center text-xl shrink-0 border border-green-200">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-gray-900">Franchise KYC Verified & Approved</h3>
                        <p class="text-xs text-gray-600 mt-1">Your identity and franchise business credentials are verified. Hub operations, bookings, and wallet settlements are fully active.</p>
                        <div class="flex flex-wrap gap-4 mt-3 text-xs text-gray-600 font-medium">
                            <span class="bg-white/80 px-2.5 py-1 rounded-md border border-green-200"><strong>Type:</strong> {{ $kyc->business_type }}</span>
                            <span class="bg-white/80 px-2.5 py-1 rounded-md border border-green-200"><strong>{{ $kyc->document_type }}:</strong> {{ $kyc->document_number }}</span>
                            <span class="bg-white/80 px-2.5 py-1 rounded-md border border-green-200"><strong>PAN:</strong> {{ $kyc->pan_number }}</span>
                            @if($kyc->gst_number)
                                <span class="bg-white/80 px-2.5 py-1 rounded-md border border-green-200"><strong>GSTIN:</strong> {{ $kyc->gst_number }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <span class="px-3.5 py-1.5 bg-green-100 text-green-800 border border-green-300 rounded-xl text-xs font-black uppercase tracking-wider flex items-center gap-1.5 shrink-0">
                    <i class="fa-solid fa-circle-check"></i> Approved
                </span>
            </div>
        @elseif(isset($kyc) && $kyc && $kyc->status === 'pending')
            <div class="bg-amber-50/80 border border-amber-200 rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-100 text-amber-700 rounded-xl flex items-center justify-center text-xl shrink-0 border border-amber-200">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-gray-900">KYC Verification Under Review</h3>
                        <p class="text-xs text-gray-600 mt-1">Our compliance team is verifying your uploaded documents. Verification typically takes 2–4 hours.</p>
                        <div class="flex flex-wrap gap-4 mt-3 text-xs text-gray-600 font-medium">
                            <span class="bg-white/80 px-2.5 py-1 rounded-md border border-amber-200"><strong>Document:</strong> {{ $kyc->document_type }} ({{ $kyc->document_number }})</span>
                            <span class="bg-white/80 px-2.5 py-1 rounded-md border border-amber-200"><strong>PAN:</strong> {{ $kyc->pan_number }}</span>
                        </div>
                    </div>
                </div>
                <span class="px-3.5 py-1.5 bg-amber-100 text-amber-800 border border-amber-300 rounded-xl text-xs font-black uppercase tracking-wider flex items-center gap-1.5 shrink-0">
                    <i class="fa-solid fa-hourglass-half"></i> Under Review
                </span>
            </div>
        @elseif(isset($kyc) && $kyc && $kyc->status === 'rejected')
            <div class="bg-red-50/80 border border-red-200 rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-red-100 text-red-700 rounded-xl flex items-center justify-center text-xl shrink-0 border border-red-200">
                        <i class="fa-solid fa-circle-exclamation"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-red-900">KYC Verification Rejected</h3>
                        <p class="text-xs text-red-700 mt-1"><strong>Reason:</strong> {{ $kyc->rejection_reason ?? 'Document quality or mismatch issue. Please re-upload clear copies.' }}</p>
                        <p class="text-[11px] text-gray-600 mt-1">Please review the reason above and re-submit your corrected documents below.</p>
                    </div>
                </div>
                <span class="px-3.5 py-1.5 bg-red-100 text-red-800 border border-red-300 rounded-xl text-xs font-black uppercase tracking-wider flex items-center gap-1.5 shrink-0">
                    <i class="fa-solid fa-ban"></i> Action Required
                </span>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
            <div class="mb-6 pb-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-[#1e1b4b] flex items-center justify-center">
                        <i class="fa-solid fa-file-shield text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-gray-900 text-lg">Franchise KYC Verification</h3>
                        <p class="text-xs text-gray-500">Provide official identity and business registration documents to activate full hub functionalities</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('hub.kyc.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Section 1: Business Details -->
                <div class="bg-gray-50/70 p-5 rounded-2xl border border-gray-200/80">
                    <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-[#1e1b4b] text-white flex items-center justify-center text-[10px]">1</span> Business & Identity Details
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Business Type <span class="text-red-500">*</span></label>
                            <select name="business_type" class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-medium text-gray-800 outline-none focus:border-indigo-600 transition" required>
                                <option value="Proprietorship" {{ (old('business_type', $kyc->business_type ?? '') === 'Proprietorship') ? 'selected' : '' }}>Sole Proprietorship</option>
                                <option value="Partnership" {{ (old('business_type', $kyc->business_type ?? '') === 'Partnership') ? 'selected' : '' }}>Partnership</option>
                                <option value="Private Limited" {{ (old('business_type', $kyc->business_type ?? '') === 'Private Limited') ? 'selected' : '' }}>Private Limited (Pvt Ltd)</option>
                                <option value="LLP" {{ (old('business_type', $kyc->business_type ?? '') === 'LLP') ? 'selected' : '' }}>LLP</option>
                                <option value="Individual" {{ (old('business_type', $kyc->business_type ?? '') === 'Individual') ? 'selected' : '' }}>Individual</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Document Type <span class="text-red-500">*</span></label>
                            <select name="document_type" class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-medium text-gray-800 outline-none focus:border-indigo-600 transition" required>
                                <option value="Aadhaar" {{ (old('document_type', $kyc->document_type ?? '') === 'Aadhaar') ? 'selected' : '' }}>Aadhaar Card</option>
                                <option value="Voter ID" {{ (old('document_type', $kyc->document_type ?? '') === 'Voter ID') ? 'selected' : '' }}>Voter ID Card</option>
                                <option value="Passport" {{ (old('document_type', $kyc->document_type ?? '') === 'Passport') ? 'selected' : '' }}>Passport</option>
                                <option value="GST" {{ (old('document_type', $kyc->document_type ?? '') === 'GST') ? 'selected' : '' }}>GST Certificate</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Document Number <span class="text-red-500">*</span></label>
                            <input type="text" name="document_number" value="{{ old('document_number', $kyc->document_number ?? '') }}" placeholder="Enter ID Number" class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold text-gray-800 uppercase outline-none focus:border-indigo-600 transition" required>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">PAN Number <span class="text-red-500">*</span></label>
                            <input type="text" maxlength="10" name="pan_number" value="{{ old('pan_number', $kyc->pan_number ?? $user->pan_number ?? '') }}" placeholder="ABCDE1234F" class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold text-gray-800 uppercase outline-none focus:border-indigo-600 transition" required>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-gray-200/60">
                        <div class="max-w-xs">
                            <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">GST Number (Optional)</label>
                            <input type="text" maxlength="15" name="gst_number" value="{{ old('gst_number', $kyc->gst_number ?? $user->gstin ?? '') }}" placeholder="22AAAAA0000A1Z5" class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold text-gray-800 uppercase outline-none focus:border-indigo-600 transition">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Document File Uploads -->
                <div class="bg-gray-50/70 p-5 rounded-2xl border border-gray-200/80">
                    <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-[#1e1b4b] text-white flex items-center justify-center text-[10px]">2</span> Upload Documents (Max 4MB: JPG, PNG, PDF)
                    </h4>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4" x-data="{
                        files: { id_front: null, id_back: null, pan_doc: null, gst_doc: null },
                        handleFile(e, type) {
                            if(e.target.files.length > 0) {
                                this.files[type] = e.target.files[0].name;
                            }
                        }
                    }">
                        <!-- ID Front -->
                        <label class="relative flex flex-col items-center justify-center p-4 bg-white border border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-indigo-600 hover:bg-indigo-50/20 transition-all group">
                            <input type="file" name="id_front" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleFile($event, 'id_front')">
                            <i class="fa-solid fa-id-card text-gray-400 text-xl mb-2 group-hover:text-indigo-600 transition"></i>
                            <span class="text-[11px] font-bold text-gray-700">ID Front <span class="text-red-500">*</span></span>
                            <span class="text-[9px] text-gray-400 mt-0.5 truncate w-full text-center px-1" x-text="files.id_front ? files.id_front : '{{ !empty($kyc->id_front_path) ? 'Uploaded' : 'Select File' }}'"></span>
                            @if(!empty($kyc->id_front_path))
                                <div class="absolute top-2 right-2 text-emerald-600 text-xs"><i class="fa-solid fa-circle-check"></i></div>
                            @endif
                        </label>
                        
                        <!-- ID Back -->
                        <label class="relative flex flex-col items-center justify-center p-4 bg-white border border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-indigo-600 hover:bg-indigo-50/20 transition-all group">
                            <input type="file" name="id_back" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleFile($event, 'id_back')">
                            <i class="fa-solid fa-id-card-clip text-gray-400 text-xl mb-2 group-hover:text-indigo-600 transition"></i>
                            <span class="text-[11px] font-bold text-gray-700">ID Back</span>
                            <span class="text-[9px] text-gray-400 mt-0.5 truncate w-full text-center px-1" x-text="files.id_back ? files.id_back : '{{ !empty($kyc->id_back_path) ? 'Uploaded' : 'Select File' }}'"></span>
                            @if(!empty($kyc->id_back_path))
                                <div class="absolute top-2 right-2 text-emerald-600 text-xs"><i class="fa-solid fa-circle-check"></i></div>
                            @endif
                        </label>

                        <!-- PAN -->
                        <label class="relative flex flex-col items-center justify-center p-4 bg-white border border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-indigo-600 hover:bg-indigo-50/20 transition-all group">
                            <input type="file" name="pan_doc" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleFile($event, 'pan_doc')">
                            <i class="fa-solid fa-credit-card text-gray-400 text-xl mb-2 group-hover:text-indigo-600 transition"></i>
                            <span class="text-[11px] font-bold text-gray-700">PAN Card</span>
                            <span class="text-[9px] text-gray-400 mt-0.5 truncate w-full text-center px-1" x-text="files.pan_doc ? files.pan_doc : '{{ !empty($kyc->pan_doc_path) ? 'Uploaded' : 'Select File' }}'"></span>
                            @if(!empty($kyc->pan_doc_path))
                                <div class="absolute top-2 right-2 text-emerald-600 text-xs"><i class="fa-solid fa-circle-check"></i></div>
                            @endif
                        </label>

                        <!-- GST -->
                        <label class="relative flex flex-col items-center justify-center p-4 bg-white border border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-indigo-600 hover:bg-indigo-50/20 transition-all group">
                            <input type="file" name="gst_doc" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleFile($event, 'gst_doc')">
                            <i class="fa-solid fa-file-invoice text-gray-400 text-xl mb-2 group-hover:text-indigo-600 transition"></i>
                            <span class="text-[11px] font-bold text-gray-700">GST Certificate</span>
                            <span class="text-[9px] text-gray-400 mt-0.5 truncate w-full text-center px-1" x-text="files.gst_doc ? files.gst_doc : '{{ !empty($kyc->gst_doc_path) ? 'Uploaded' : 'Optional' }}'"></span>
                            @if(!empty($kyc->gst_doc_path))
                                <div class="absolute top-2 right-2 text-emerald-600 text-xs"><i class="fa-solid fa-circle-check"></i></div>
                            @endif
                        </label>
                    </div>
                </div>

                <!-- Section 3: Bank Details for COD Remittance & Settlements -->
                <div class="bg-gray-50/70 p-5 rounded-2xl border border-gray-200/80">
                    <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-[#1e1b4b] text-white flex items-center justify-center text-[10px]">3</span> Bank Settlement Account
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Bank Name</label>
                            <input type="text" name="bank_name" value="{{ old('bank_name', $user->bank_name ?? '') }}" placeholder="e.g. HDFC Bank" class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-medium text-gray-800 outline-none focus:border-indigo-600 transition">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Account Number</label>
                            <input type="text" name="account_number" value="{{ old('account_number', $user->account_number ?? '') }}" placeholder="e.g. 501002345678" class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold text-gray-800 outline-none focus:border-indigo-600 transition">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">IFSC Code</label>
                            <input type="text" maxlength="11" name="ifsc_code" value="{{ old('ifsc_code', $user->ifsc_code ?? '') }}" placeholder="HDFC0001234" class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold text-gray-800 uppercase outline-none focus:border-indigo-600 transition">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Account Holder Name</label>
                            <input type="text" name="account_holder_name" value="{{ old('account_holder_name', $user->account_holder_name ?? $user->name ?? '') }}" placeholder="Account Name" class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-medium text-gray-800 outline-none focus:border-indigo-600 transition">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end items-center gap-3 pt-4 border-t border-gray-100">
                    <button type="submit" class="px-8 py-3 bg-[#1e1b4b] hover:bg-black text-white font-extrabold rounded-xl text-sm shadow-md transition flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>{{ (isset($kyc) && $kyc && $kyc->status === 'approved') ? 'Update KYC Details' : 'Submit KYC for Verification' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function hubProfileData() {
    const params = new URLSearchParams(window.location.search);
    const viewParam = params.get('view');
    const hasKycNotice = {{ (session('error') && str_contains(session('error'), 'KYC')) ? 'true' : 'false' }};
    let initialTab = 'profile';
    if (viewParam === 'kyc' || hasKycNotice) {
        initialTab = 'kyc';
    } else if (viewParam === 'warehouses') {
        initialTab = 'warehouses';
    }
    return {
        tab: initialTab,
        showWhModal: false,
        whLoading: false,
        whError: '',
        whForm: {
            id: null,
            name: '',
            contact_person: '',
            phone: '',
            address: '',
            pincode: '',
            city: '',
            state: ''
        },
        openWhModal(wh = null) {
            this.whError = '';
            if (wh) {
                this.whForm = {
                    id: wh.id,
                    name: wh.name || '',
                    contact_person: wh.contact_person || '',
                    phone: wh.phone || '',
                    address: wh.address || '',
                    pincode: wh.pincode || '',
                    city: wh.city || '',
                    state: wh.state || ''
                };
            } else {
                this.whForm = {
                    id: null,
                    name: '',
                    contact_person: '{{ Auth::user()->name }}',
                    phone: '{{ Auth::user()->phone }}',
                    address: '',
                    pincode: '',
                    city: '',
                    state: ''
                };
            }
            this.showWhModal = true;
        },
        async fetchWhPincode() {
            if (this.whForm.pincode && this.whForm.pincode.length === 6) {
                try {
                    const res = await fetch('https://api.postalpincode.in/pincode/' + this.whForm.pincode);
                    const data = await res.json();
                    if (data && data[0] && data[0].Status === 'Success') {
                        this.whForm.city = data[0].PostOffice[0].District;
                        this.whForm.state = data[0].PostOffice[0].State;
                    }
                } catch(e) {
                    console.error('Pincode lookup error:', e);
                }
            }
        },
        async saveWarehouse() {
            this.whLoading = true;
            this.whError = '';
            const isEdit = !!this.whForm.id;
            const url = isEdit ? `/hub/warehouses/${this.whForm.id}` : '{{ route('hub.warehouses.store') }}';
            const method = isEdit ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(this.whForm)
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    this.showWhModal = false;
                    window.location.href = '{{ route('hub.profile') }}?view=warehouses';
                } else {
                    let msg = data.message || 'Validation failed';
                    if (data.errors) {
                        msg = Object.values(data.errors).flat().join(' ');
                    }
                    this.whError = msg;
                }
            } catch(e) {
                this.whError = 'Error saving warehouse: ' + e.message;
            } finally {
                this.whLoading = false;
            }
        },
        async setDefaultWh(id) {
            try {
                const res = await fetch(`/hub/warehouses/${id}/default`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await res.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to update default warehouse');
                }
            } catch(e) {
                alert('Error updating default warehouse: ' + e.message);
            }
        },
        async deleteWh(id) {
            if (!confirm('Are you sure you want to delete this warehouse?')) return;
            try {
                const res = await fetch(`/hub/warehouses/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await res.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to delete warehouse');
                }
            } catch(e) {
                alert('Error deleting warehouse: ' + e.message);
            }
        }
    };
}
</script>
@endsection

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
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 font-bold rounded-xl border border-green-200">
            <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
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
</div>

<script>
function hubProfileData() {
    const params = new URLSearchParams(window.location.search);
    return {
        tab: params.get('view') === 'warehouses' ? 'warehouses' : 'profile',
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

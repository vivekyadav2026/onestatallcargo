@extends('layouts.admin')
@section('title', 'Fleet & Rider Management - OneStall Cargo')
@section('content')
<div class="space-y-6" x-data="{ showEditModal: false, editRider: null }">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Fleet Management</h1>
            <p class="text-sm text-gray-500 mt-1">Manage global Pickup and Delivery Riders.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 font-bold rounded-xl border border-green-200 text-sm">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="p-4 bg-red-50 text-red-700 font-bold rounded-xl border border-red-200 text-sm">
            <i class="fa-solid fa-triangle-exclamation mr-1"></i> 
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Add New Rider Form -->
        <div class="xl:col-span-1">
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">
                <h2 class="font-extrabold text-gray-800 mb-4"><i class="fa-solid fa-user-plus mr-2 text-[var(--gold-deep)]"></i> Register New Rider</h2>
                <form action="{{ route('admin.riders.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[var(--gold)] outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[var(--gold)] outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Phone Number</label>
                            <input type="text" name="phone" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[var(--gold)] outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[var(--gold)] outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Assign Franchise</label>
                            <select name="franchise_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[var(--gold)] outline-none">
                                <option value="">Global / Unassigned</option>
                                @foreach($franchises as $franchise)
                                    <option value="{{ $franchise->id }}">{{ optional($franchise->user)->company_name ?? optional($franchise->user)->name ?? 'Orphan Franchise' }} ({{ $franchise->city ?? 'No City' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Assign Hub</label>
                            <select name="hub_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[var(--gold)] outline-none">
                                <option value="">Global / Unassigned</option>
                                @foreach($hubs as $hub)
                                    <option value="{{ $hub->id }}">{{ $hub->name }} ({{ $hub->city }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Rider Type</label>
            <select name="role" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[var(--gold)] outline-none">
                <option value="rider">Hybrid (Standard)</option>
                <option value="pickup_rider">Pickup Only</option>
                <option value="delivery_rider">Delivery Only</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Vehicle Type</label>
            <input type="text" name="vehicle_type" placeholder="Bike, Van" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[var(--gold)] outline-none">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Vehicle Number</label>
            <input type="text" name="vehicle_number" placeholder="DL-12-AB-3456" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[var(--gold)] outline-none">
        </div>
    </div>
                    <button type="submit" class="w-full py-2.5 bg-gray-900 hover:bg-black text-white font-bold rounded-lg text-sm transition">
                        Create Rider Profile
                    </button>
                </form>
            </div>
        </div>

        <!-- Rider List -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead>
                            <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                                <th class="px-6 py-4">Rider Details</th>
                                <th class="px-6 py-4">Assignment</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-gray-700">
                            @forelse($riders as $rider)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ optional($rider->user)->name ?? 'Deleted User' }}</div>
                                    <div class="text-[10px] text-gray-400"><i class="fa-solid fa-phone mr-1"></i> {{ optional($rider->user)->phone ?? 'N/A' }}</div>
                                    <div class="text-[10px] text-gray-400"><i class="fa-solid fa-envelope mr-1"></i> {{ optional($rider->user)->email ?? 'Deleted' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-gray-800">{{ $rider->franchise ? (optional($rider->franchise->user)->company_name ?? optional($rider->franchise->user)->name ?? 'Orphan') : 'Internal / Global' }}</div>
                                    <div class="text-[10px] text-gray-500"><i class="fa-solid fa-building mr-1"></i> {{ $rider->hub->name ?? 'No Hub' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded {{ $rider->status === 'active' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }} text-[10px] font-bold uppercase tracking-widest">{{ $rider->status }}</span>
                                    <div class="text-[10px] text-gray-400 mt-1">{{ optional($rider->user)->role ? str_replace('_', ' ', $rider->user->role) : '' }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button @click="editRider = {{ $rider->id }}; showEditModal = true" class="text-blue-500 hover:text-blue-700 font-bold text-xs bg-blue-50 px-2 py-1 rounded"><i class="fa-solid fa-pen"></i></button>
                                        <form action="{{ route('admin.riders.destroy', $rider->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to completely delete this rider?');" class="inline-block">
                                            @csrf
                                            <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-xs bg-red-50 px-2 py-1 rounded"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal for Admin -->
                            <div x-show="editRider === {{ $rider->id }}" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                                    <div x-show="editRider === {{ $rider->id }}" class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" @click="editRider = null; showEditModal = false"></div>
                                    <div x-show="editRider === {{ $rider->id }}" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                        <form action="{{ route('admin.riders.update', $rider->id) }}" method="POST">
                                            @csrf
                                            <div class="px-6 py-5">
                                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                                                    <h3 class="text-lg font-bold text-gray-900">Edit Rider: {{ optional($rider->user)->name ?? 'Deleted User' }}</h3>
                                                    <button type="button" @click="editRider = null; showEditModal = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-times"></i></button>
                                                </div>
                                                <div class="space-y-4">
                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-700 mb-1">Name</label>
                                                        <input type="text" name="name" value="{{ optional($rider->user)->name ?? 'Deleted User' }}" required class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-xs font-bold text-gray-700 mb-1">Phone</label>
                                                            <input type="text" name="phone" value="{{ optional($rider->user)->phone }}" required class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-bold text-gray-700 mb-1">Reset Password</label>
                                                            <input type="text" name="password" placeholder="Leave blank to keep current" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                                                            <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                                                                <option value="active" {{ $rider->status === 'active' ? 'selected' : '' }}>Active</option>
                                                                <option value="suspended" {{ $rider->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-bold text-gray-700 mb-1">Assign Franchise</label>
                                                            <select name="franchise_id" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                                                                <option value="">Global / Unassigned</option>
                                                                @foreach($franchises as $franchise)
                                                                    <option value="{{ $franchise->id }}" {{ $rider->franchise_id == $franchise->id ? 'selected' : '' }}>{{ optional($franchise->user)->company_name ?? optional($franchise->user)->name ?? 'Orphan Franchise' }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Vehicle Type</label>
            <input type="text" name="vehicle_type" value="{{ $rider->vehicle_type }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Vehicle Number</label>
            <input type="text" name="vehicle_number" value="{{ $rider->vehicle_number }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Assign Hub</label>
            <select name="hub_id" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                <option value="">Global / Unassigned</option>
                @foreach($hubs as $hub)
                    <option value="{{ $hub->id }}" {{ $rider->hub_id == $hub->id ? 'selected' : '' }}>{{ $hub->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
                                                </div>
                                            </div>
                                            <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-200">
                                                <button type="button" @click="editRider = null; showEditModal = false" class="px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-100 rounded-lg">Cancel</button>
                                                <button type="submit" class="px-5 py-2 text-sm font-bold text-white bg-gray-900 hover:bg-black rounded-lg">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-motorcycle text-4xl mb-3 text-gray-200"></i>
                                        <p>No riders registered in the system.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-200">
                    {{ $riders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

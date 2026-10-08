@extends('layouts.hub')

@section('content')
<div class="space-y-6" x-data="{ showAddRider: false, editRider: null, viewRider: null }">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-extrabold text-gray-900">Fleet Management</h2>
        <button @click="showAddRider = true" class="px-5 py-2.5 bg-[var(--gold)] text-gray-900 font-bold rounded-xl shadow-md hover:bg-yellow-500 transition">
            <i class="fa-solid fa-plus mr-1"></i> Add Rider
        </button>
    </div>

    <!-- Add Rider Modal -->
    <div x-show="showAddRider" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div x-show="showAddRider" class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" @click="showAddRider = false"></div>
            <div x-show="showAddRider" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('hub.fleet.store') }}" method="POST">
                    @csrf
                    <div class="px-6 py-5">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Add New Rider</h3>
                            <button type="button" @click="showAddRider = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-times"></i></button>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Name</label>
                                <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]" placeholder="Rider Name">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Email (Login)</label>
                                    <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]" placeholder="rider@email.com">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Phone</label>
                                    <input type="text" name="phone" required class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]" placeholder="Phone Number">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Password</label>
                                <input type="password" name="password" required minlength="6" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]" placeholder="Min 6 characters">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Role Type</label>
                                    <select name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                                        <option value="rider">Standard Rider</option>
                                        <option value="pickup_rider">Pickup Only</option>
                                        <option value="delivery_rider">Delivery Only</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Assign Hub</label>
                                    <select name="hub_id" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                                        <option value="">-- No Hub --</option>
                                        @foreach($hubs as $hub)
                                        <option value="{{ $hub->id }}">{{ $hub->name }} ({{ $hub->city }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Vehicle Type (Optional)</label>
                                    <input type="text" name="vehicle_type" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]" placeholder="e.g., Bike, Van">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Vehicle No. (Optional)</label>
                                    <input type="text" name="vehicle_number" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]" placeholder="e.g., DL-12-AB-3456">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-200">
                        <button type="button" @click="showAddRider = false" class="px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-100 rounded-lg">Cancel</button>
                        <button type="submit" class="px-5 py-2 text-sm font-bold text-white bg-gray-900 hover:bg-black rounded-lg">Create Rider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Riders Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <h3 class="font-bold text-gray-800">Your Fleet</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 text-[10px] font-extrabold uppercase text-gray-500 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3">Rider Name</th>
                        <th class="px-4 py-3">Phone & Email</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Hub Assigned</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($riders as $rider)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-bold text-gray-900">{{ $rider->user->name }}</td>
                        <td class="px-4 py-3">
                            <div>{{ $rider->user->phone }}</div>
                            <div class="text-xs text-gray-500">{{ $rider->user->email }}</div>
                        </td>
                        <td class="px-4 py-3 uppercase text-xs">{{ str_replace('_', ' ', $rider->user->role) }}</td>
                        <td class="px-4 py-3">{{ $rider->hub->name ?? 'Unassigned' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-md text-[10px] font-bold tracking-wider {{ $rider->status === 'active' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                {{ $rider->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right flex justify-end gap-2">
                            <button @click="viewRider = {{ $rider->id }}" class="text-gray-600 font-bold hover:underline text-xs bg-gray-100 px-2 py-1 rounded">View</button>
                            <button @click="editRider = {{ $rider->id }}" class="text-blue-600 font-bold hover:underline text-xs bg-blue-50 px-2 py-1 rounded">Edit</button>
                            <form action="{{ route('hub.fleet.destroy', $rider->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this rider?');">
                                @csrf
                                <button type="submit" class="text-red-600 font-bold hover:underline text-xs bg-red-50 px-2 py-1 rounded">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal for this Rider -->
                    <div x-show="editRider === {{ $rider->id }}" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                            <div x-show="editRider === {{ $rider->id }}" class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" @click="editRider = null"></div>
                            <div x-show="editRider === {{ $rider->id }}" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                <form action="{{ route('hub.fleet.update', $rider->id) }}" method="POST">
                                    @csrf
                                    <div class="px-6 py-5">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-bold text-gray-900">Edit Rider: {{ $rider->user->name }}</h3>
                                            <button type="button" @click="editRider = null" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-times"></i></button>
                                        </div>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-700 mb-1">Name</label>
                                                <input type="text" name="name" value="{{ $rider->user->name }}" required class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                                            </div>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 mb-1">Phone</label>
                                                    <input type="text" name="phone" value="{{ $rider->user->phone }}" required class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
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
                                                    <label class="block text-xs font-bold text-gray-700 mb-1">Assign Hub</label>
                                                    <select name="hub_id" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                                                        <option value="">-- No Hub --</option>
                                                        @foreach($hubs as $hub)
                                                        <option value="{{ $hub->id }}" {{ $rider->hub_id == $hub->id ? 'selected' : '' }}>{{ $hub->name }} ({{ $hub->city }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 mb-1">Vehicle Type</label>
                                                    <input type="text" name="vehicle_type" value="{{ $rider->vehicle_type }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 mb-1">Vehicle No.</label>
                                                    <input type="text" name="vehicle_number" value="{{ $rider->vehicle_number }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-200">
                                        <button type="button" @click="editRider = null" class="px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-100 rounded-lg">Cancel</button>
                                        <button type="submit" class="px-5 py-2 text-sm font-bold text-white bg-gray-900 hover:bg-black rounded-lg">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- View Modal for this Rider -->
                    <div x-show="viewRider === {{ $rider->id }}" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                            <div x-show="viewRider === {{ $rider->id }}" class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" @click="viewRider = null"></div>
                            <div x-show="viewRider === {{ $rider->id }}" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                <div class="px-6 py-5">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg font-bold text-gray-900">Rider Profile: {{ $rider->user->name }}</h3>
                                        <button type="button" @click="viewRider = null" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-times"></i></button>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                                            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 text-2xl font-bold">
                                                {{ substr($rider->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 text-lg">{{ $rider->user->name }}</div>
                                                <div class="text-sm text-gray-500 uppercase font-bold">{{ str_replace('_', ' ', $rider->user->role) }}</div>
                                            </div>
                                            <div class="ml-auto">
                                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $rider->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                    {{ strtoupper($rider->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="p-3 bg-gray-50 rounded-lg">
                                                <div class="text-[10px] text-gray-500 font-bold uppercase mb-1">Contact Information</div>
                                                <div class="text-sm font-bold text-gray-800"><i class="fa-solid fa-phone mr-2 text-gray-400"></i>{{ $rider->user->phone }}</div>
                                                <div class="text-sm text-gray-600"><i class="fa-solid fa-envelope mr-2 text-gray-400"></i>{{ $rider->user->email }}</div>
                                            </div>
                                            <div class="p-3 bg-gray-50 rounded-lg">
                                                <div class="text-[10px] text-gray-500 font-bold uppercase mb-1">Vehicle Details</div>
                                                <div class="text-sm font-bold text-gray-800"><i class="fa-solid fa-motorcycle mr-2 text-gray-400"></i>{{ $rider->vehicle_type ?? 'N/A' }}</div>
                                                <div class="text-sm text-gray-600"><i class="fa-solid fa-id-card mr-2 text-gray-400"></i>{{ $rider->vehicle_number ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <div class="text-[10px] text-gray-500 font-bold uppercase mb-1">Hub Assignment</div>
                                            <div class="text-sm font-bold text-gray-800"><i class="fa-solid fa-building mr-2 text-gray-400"></i>{{ $rider->hub->name ?? 'Unassigned' }}</div>
                                            <div class="text-xs text-gray-500 mt-1">Joined: {{ $rider->created_at->format('d M Y, h:i A') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-200">
                                    <button type="button" @click="viewRider = null" class="px-5 py-2 text-sm font-bold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No riders found in your fleet.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-3 border-t border-gray-100">{{ $riders->links() }}</div>
        </div>
    </div>
</div>
@endsection


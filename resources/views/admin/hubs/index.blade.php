@extends('layouts.admin')

@section('title', 'Hubs & Franchises - OneStall Cargo')

@section('content')
<div class="space-y-6" x-data="{ showAddModal: false }">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Hubs & Franchises</h1>
            <p class="text-sm text-gray-500 mt-1">Manage network hubs, sorting centers, and regional franchises</p>
        </div>
        <div class="flex gap-2">
            <button @click="showAddModal = true" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[var(--gold)] text-gray-900 shadow-md hover:bg-[var(--gold-deep)] transition-colors"><i class="fa-solid fa-plus"></i> Add New Hub</button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-700 border border-green-200 font-bold text-sm">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-700 border border-red-200 font-bold text-sm">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Data Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <th class="px-6 py-4">Hub Code</th>
                        <th class="px-6 py-4">Hub Name</th>
                        <th class="px-6 py-4">City / Pincode</th>
                        <th class="px-6 py-4">Capacity</th>
                        <th class="px-6 py-4">Franchise Manager</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700 font-medium">
                    @forelse($hubs as $hub)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-[var(--gold-deep)]">{{ $hub->hub_code }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $hub->name }}</td>
                            <td class="px-6 py-4">{{ $hub->city }} ({{ $hub->pincode }})</td>
                            <td class="px-6 py-4">{{ number_format($hub->capacity) }} units/day</td>
                            <td class="px-6 py-4">
                                @if($hub->manager)
                                    <div class="font-bold text-gray-900">{{ $hub->manager->name }}</div>
                                    <div class="text-[10px] text-gray-500">{{ $hub->manager->phone ?? $hub->manager->email }}</div>
                                @else
                                    <span class="text-gray-400 italic">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $hub->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                    {{ $hub->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.hubs.toggle', $hub->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold uppercase {{ $hub->is_active ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800' }}">
                                        {{ $hub->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-building text-4xl mb-3 text-gray-200"></i>
                                    <p>No Hubs or Franchises configured yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($hubs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $hubs->links() }}
            </div>
        @endif
    </div>

    <!-- Add Hub Modal -->
    <div x-show="showAddModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showAddModal = false">
                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
            </div>
            
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('admin.hubs.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-xl leading-6 font-extrabold text-gray-900 mb-6 border-b border-gray-100 pb-4">Register New Hub / Franchise</h3>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Hub Code (e.g. DEL-01)</label>
                                    <input type="text" name="hub_code" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none uppercase">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Name / Branch</label>
                                    <input type="text" name="name" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Base City</label>
                                    <input type="text" name="city" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Service Pincode</label>
                                    <input type="text" name="pincode" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Daily Capacity (Parcels)</label>
                                <input type="number" name="capacity" value="500" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Assign Franchise Manager (Optional)</label>
                                <select name="manager_id" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                                    <option value="">-- No Manager Assigned --</option>
                                    @foreach($managers as $manager)
                                        <option value="{{ $manager->id }}">{{ $manager->name }} ({{ $manager->email }})</option>
                                    @endforeach
                                </select>
                                <p class="text-[10px] text-gray-500 mt-1">Only users with the 'Franchise' role appear here.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 gap-2">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-2.5 bg-gray-900 text-base font-bold text-white hover:bg-black sm:w-auto sm:text-sm">
                            Register Hub
                        </button>
                        <button type="button" @click="showAddModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

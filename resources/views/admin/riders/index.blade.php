@extends('layouts.admin')
@section('title', 'Fleet & Rider Management - OneStall Cargo')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Fleet Management</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your Pickup and Delivery Riders.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 font-bold rounded-xl border border-green-200 text-sm">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add New Rider Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">
                <h2 class="font-extrabold text-gray-800 mb-4"><i class="fa-solid fa-user-plus mr-2 text-[var(--gold-deep)]"></i> Register New Rider</h2>
                <form action="{{ route('admin.riders.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[var(--gold)] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[var(--gold)] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="phone" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[var(--gold)] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[var(--gold)] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Rider Type</label>
                        <select name="role" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[var(--gold)] outline-none">
                            <option value="pickup_rider">Pickup Rider</option>
                            <option value="delivery_rider">Delivery Rider</option>
                            <option value="rider">Hybrid Rider</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-2 bg-gray-900 hover:bg-black text-white font-bold rounded-lg text-sm transition">
                        Create Profile
                    </button>
                </form>
            </div>
        </div>

        <!-- Rider List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead>
                            <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                                <th class="px-6 py-4">Rider Details</th>
                                <th class="px-6 py-4">Role</th>
                                <th class="px-6 py-4">GPS Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-gray-700">
                            @forelse($riders as $rider)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $rider->name }}</div>
                                    <div class="text-[10px] text-gray-400"><i class="fa-solid fa-phone mr-1"></i> {{ $rider->phone ?? 'N/A' }}</div>
                                    <div class="text-[10px] text-gray-400"><i class="fa-solid fa-envelope mr-1"></i> {{ $rider->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-widest">{{ str_replace('_', ' ', $rider->role) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($rider->latitude && $rider->longitude)
                                        <span class="text-green-600 font-bold text-xs"><i class="fa-solid fa-location-dot"></i> Active</span>
                                        <div class="text-[10px] text-gray-400 mt-0.5">Updated {{ \Carbon\Carbon::parse($rider->last_location_at)->diffForHumans() }}</div>
                                    @else
                                        <span class="text-gray-400 font-bold text-xs"><i class="fa-solid fa-location-crosshairs"></i> Offline</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-red-500 hover:text-red-700 font-bold text-xs"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
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
            </div>
        </div>
    </div>
</div>
@endsection

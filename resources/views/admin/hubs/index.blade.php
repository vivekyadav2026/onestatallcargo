@extends('layouts.admin')

@section('title', 'Hubs & Franchises - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Hubs & Franchises</h1>
            <p class="text-sm text-gray-500 mt-1">Manage network hubs, sorting centers, and regional franchises</p>
        </div>
        <div class="flex gap-2">
            <button class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[var(--gold)] text-gray-900 shadow-md hover:bg-[var(--gold-deep)] transition-colors"><i class="fa-solid fa-plus"></i> Add New Hub</button>
        </div>
    </div>

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
                        <th class="px-6 py-4">Manager</th>
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
                                    {{ $hub->manager->name }} <br><span class="text-xs text-gray-500">{{ $hub->manager->phone }}</span>
                                @else
                                    <span class="text-gray-400">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $hub->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                    {{ $hub->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button class="text-[var(--gold-deep)] hover:text-yellow-600 font-bold text-xs"><i class="fa-solid fa-list-check"></i> Assign Pincodes</button>
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
</div>
@endsection


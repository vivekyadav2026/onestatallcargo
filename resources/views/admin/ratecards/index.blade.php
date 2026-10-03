@extends('layouts.admin')

@section('title', 'Rate Engine - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Rate Engine & Pricing</h1>
            <p class="text-sm text-gray-500 mt-1">Manage shipping rate cards, zone pricing, and active tariff versions.</p>
        </div>
        <a href="{{ route('admin.ratecards.create') }}" class="w-full sm:w-auto px-5 py-2.5 bg-[var(--gold)] text-gray-900 font-bold rounded-xl shadow-sm hover:bg-yellow-500 transition text-sm text-center">
            <i class="fa-solid fa-plus mr-1"></i> Create New Rate Card
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-green-50 text-green-700 border border-green-200 font-bold text-sm shadow-sm">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-xl bg-red-50 text-red-700 border border-red-200 font-bold text-sm shadow-sm">
            <i class="fa-solid fa-circle-xmark mr-1"></i> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 bg-gray-50 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h3 class="font-bold text-gray-800"><i class="fa-solid fa-indian-rupee-sign mr-2 text-[var(--gold-deep)]"></i> All Tariff Versions</h3>
            
            <form action="{{ route('admin.ratecards.index') }}" method="GET" class="flex flex-col sm:flex-row w-full sm:w-auto items-center gap-3">
                <select name="status" class="w-full sm:w-auto px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm focus:outline-none focus:border-[var(--gold)] font-bold text-gray-700" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                </select>
                @if(request('status'))
                    <a href="{{ route('admin.ratecards.index') }}" class="text-xs text-gray-500 hover:text-gray-900 font-bold">Clear Filter</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-white text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-100">
                        <th class="px-6 py-4">Version Name</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Effective Date</th>
                        <th class="px-6 py-4">Zones Configured</th>
                        <th class="px-6 py-4">Created On</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700 font-medium">
                    @forelse($rateCards as $card)
                    <tr class="hover:bg-gray-50 transition-colors {{ $card->is_active ? 'bg-green-50/20' : '' }}">
                        <td class="px-6 py-4">
                            <div class="font-black text-gray-900 text-base">{{ $card->version_name }}</div>
                            <div class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">ID: {{ str_pad($card->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($card->is_active)
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-[10px] font-extrabold uppercase rounded-full tracking-wider border border-green-200 shadow-sm"><i class="fa-solid fa-bolt text-yellow-500 mr-1"></i> Live Active</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-500 text-[10px] font-bold uppercase rounded-full tracking-wider border border-gray-200">Inactive Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($card->effective_from)->format('d M, Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $card->zones_count }} Zones
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-gray-500">{{ $card->created_at->format('d M, Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <a href="{{ route('admin.ratecards.show', $card->id) }}" class="text-gray-400 hover:text-gray-900 transition" title="Preview Pricing"><i class="fa-solid fa-eye text-lg"></i></a>
                            
                            @if(!$card->is_active)
                                <a href="{{ route('admin.ratecards.edit', $card->id) }}" class="text-gray-400 hover:text-blue-600 transition" title="Edit Draft"><i class="fa-solid fa-pen text-lg"></i></a>
                                
                                <form action="{{ route('admin.ratecards.activate', $card->id) }}" method="POST" onsubmit="return confirm('WARNING: Activating this rate card will immediately replace the current live pricing for all new shipments. Do you wish to proceed?');" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-400 hover:text-green-600 transition" title="Go Live (Activate)"><i class="fa-solid fa-circle-check text-lg"></i></button>
                                </form>
                            @endif
                            
                            <form action="{{ route('admin.ratecards.duplicate', $card->id) }}" method="POST" onsubmit="return confirm('Create a duplicate copy of this rate card?');" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-400 hover:text-purple-600 transition" title="Duplicate to New Draft"><i class="fa-solid fa-copy text-lg"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-3xl mb-4 shadow-sm border border-gray-100">
                                    <i class="fa-solid fa-indian-rupee-sign text-gray-300"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-700 mb-1">No Rate Cards Configured</h3>
                                <p class="text-sm">You haven't created any pricing models yet.</p>
                                <a href="{{ route('admin.ratecards.create') }}" class="mt-4 px-5 py-2 bg-gray-900 text-white font-bold rounded-xl shadow text-sm hover:bg-black transition">Create Initial Rate Card</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rateCards->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $rateCards->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

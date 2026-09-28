@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Rate Card Management</h1>
        <a href="{{ route('admin.ratecards.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
            <i class="fa-solid fa-plus mr-2"></i> Create New Rate Card
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 shadow" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 shadow" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b">
            <form action="{{ route('admin.ratecards.index') }}" method="GET" class="flex items-center gap-4">
                <select name="status" class="border rounded px-3 py-2" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                </select>
                <a href="{{ route('admin.ratecards.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">Clear</a>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-sm uppercase">
                        <th class="p-4 font-semibold border-b">Version Name</th>
                        <th class="p-4 font-semibold border-b">Status</th>
                        <th class="p-4 font-semibold border-b">Effective From</th>
                        <th class="p-4 font-semibold border-b">Zones</th>
                        <th class="p-4 font-semibold border-b">Created At</th>
                        <th class="p-4 font-semibold border-b">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($rateCards as $card)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4 font-medium">{{ $card->version_name }}</td>
                        <td class="p-4">
                            @if($card->is_active)
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-green-400">Active</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-gray-300">Inactive</span>
                            @endif
                        </td>
                        <td class="p-4">{{ \Carbon\Carbon::parse($card->effective_from)->format('d M Y') }}</td>
                        <td class="p-4">{{ $card->zones_count }} Zones configured</td>
                        <td class="p-4">{{ $card->created_at->format('d M Y H:i') }}</td>
                        <td class="p-4">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.ratecards.show', $card->id) }}" class="text-blue-600 hover:text-blue-900" title="View & Preview"><i class="fa-solid fa-eye"></i></a>
                                @if(!$card->is_active)
                                    <a href="{{ route('admin.ratecards.edit', $card->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                    
                                    <form action="{{ route('admin.ratecards.activate', $card->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to activate this rate card? All future shipments will use this pricing.');" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900" title="Activate"><i class="fa-solid fa-check-circle"></i></button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.ratecards.duplicate', $card->id) }}" method="POST" onsubmit="return confirm('Duplicate this rate card?');" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-600 hover:text-gray-900" title="Duplicate Version"><i class="fa-solid fa-copy"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-file-invoice-dollar text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg">No Rate Cards found.</p>
                                <a href="{{ route('admin.ratecards.create') }}" class="text-blue-600 hover:underline mt-2">Create the first official Rate Card</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $rateCards->links() }}
        </div>
    </div>
</div>
@endsection

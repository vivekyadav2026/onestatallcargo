@extends('layouts.admin')
@section('title', 'Sales & Contact Leads - OneStall Cargo')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Sales & Contact Leads</h1>
</div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
                @endif
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-gray-700 text-xs uppercase">
                            <tr>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Name / Company</th>
                                <th class="px-4 py-3">Contact</th>
                                <th class="px-4 py-3">Volume</th>
                                <th class="px-4 py-3">Message</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leads as $lead)
                                <tr class="border-b">
                                    <td class="px-4 py-3">{{ $lead->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-4 py-3 font-bold">{{ $lead->name }}<br><span class="text-xs text-gray-400 font-normal">{{ $lead->company_name ?? '-' }}</span></td>
                                    <td class="px-4 py-3">{{ $lead->email }}<br>{{ $lead->phone }}</td>
                                    <td class="px-4 py-3">{{ $lead->volume ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 max-w-xs truncate" title="{{ $lead->message }}">{{ Str::limit($lead->message, 30) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded text-xs font-bold {{ $lead->status == 'New' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                            {{ $lead->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($lead->status == 'New')
                                        <form action="{{ route('admin.contacts.action', $lead->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="Reviewed">
                                            <button type="submit" class="text-xs bg-blue-600 text-white px-2 py-1 rounded">Mark Reviewed</button>
                                        </form>
                                        @else
                                        <span class="text-xs text-gray-500">Reviewed</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">No leads found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $leads->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection

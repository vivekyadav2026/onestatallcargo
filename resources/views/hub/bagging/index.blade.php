@extends('layouts.hub')

@section('content')
<div class="space-y-6" x-data="{ showCreateBag: false }">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-extrabold text-gray-900">Dispatch & Bagging</h2>
        <button @click="showCreateBag = true" class="px-5 py-2.5 bg-[var(--gold)] text-gray-900 font-bold rounded-xl shadow-md hover:bg-yellow-500 transition">
            <i class="fa-solid fa-plus mr-1"></i> Create Bag
        </button>
    </div>

    <!-- Create Bag Modal -->
    <div x-show="showCreateBag" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div x-show="showCreateBag" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" @click="showCreateBag = false"></div>
            <div x-show="showCreateBag" x-transition class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('hub.bagging.store') }}" method="POST">
                    @csrf
                    <div class="px-6 py-5">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Create New Bag</h3>
                            <button type="button" @click="showCreateBag = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-times"></i></button>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Destination Hub</label>
                            <select name="destination_hub_id" required class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                                <option value="">-- Select Destination Hub --</option>
                                @foreach($hubs as $destHub)
                                    <option value="{{ $destHub->id }}">{{ $destHub->name }} ({{ $destHub->city }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-200">
                        <button type="button" @click="showCreateBag = false" class="px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-100 rounded-lg">Cancel</button>
                        <button type="submit" class="px-5 py-2 text-sm font-bold text-white bg-gray-900 hover:bg-black rounded-lg">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Bags Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h3 class="font-bold text-gray-800">Your Bags</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-gray-50 text-[10px] font-extrabold uppercase text-gray-500 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3">Bag No.</th>
                            <th class="px-4 py-3">Destination</th>
                            <th class="px-4 py-3">Count</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium">
                        @forelse($bags as $bag)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-bold text-gray-900">{{ $bag->bag_number }}</td>
                            <td class="px-4 py-3">{{ optional($bag->destinationHub)->name ?? 'Unknown' }} ({{ optional($bag->destinationHub)->city ?? '' }})</td>
                            <td class="px-4 py-3">{{ $bag->shipments_count }} items</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold tracking-wider {{ $bag->status === 'OPEN' ? 'bg-blue-50 text-blue-700' : ($bag->status === 'SEALED' ? 'bg-yellow-50 text-yellow-700' : 'bg-green-50 text-green-700') }}">
                                    {{ $bag->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('hub.bagging.show', $bag->id) }}" class="text-blue-600 font-bold hover:underline text-xs">View/Manage</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No bags found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-3 border-t border-gray-100">{{ $bags->links() }}</div>
            </div>
        </div>

        <!-- Manifests Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h3 class="font-bold text-gray-800">Your Manifests (Dispatch)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-gray-50 text-[10px] font-extrabold uppercase text-gray-500 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3">Manifest No.</th>
                            <th class="px-4 py-3">Destination</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium">
                        @forelse($manifests as $manifest)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-bold text-gray-900">{{ $manifest->manifest_number }}</td>
                            <td class="px-4 py-3">{{ optional($manifest->destinationHub)->name ?? 'Unknown' }} ({{ optional($manifest->destinationHub)->city ?? '' }})</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold tracking-wider {{ $manifest->status === 'CREATED' ? 'bg-yellow-50 text-yellow-700' : 'bg-green-50 text-green-700' }}">
                                    {{ $manifest->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('hub.manifests.show', $manifest->id) }}" class="text-blue-600 font-bold hover:underline text-xs">View/Print</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No manifests found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-3 border-t border-gray-100">{{ $manifests->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection



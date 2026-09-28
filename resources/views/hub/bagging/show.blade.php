@extends('layouts.hub')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('hub.bagging.index') }}" class="text-gray-400 hover:text-gray-900"><i class="fa-solid fa-arrow-left"></i></a>
            <h2 class="text-2xl font-extrabold text-gray-900">Bag: {{ $bag->bag_number }}</h2>
            <span class="px-3 py-1 rounded-full text-xs font-bold tracking-wider {{ $bag->status === 'OPEN' ? 'bg-blue-50 text-blue-700' : ($bag->status === 'SEALED' ? 'bg-yellow-50 text-yellow-700' : 'bg-green-50 text-green-700') }}">
                {{ $bag->status }}
            </span>
        </div>
        
        <div class="flex gap-2">
            @if($bag->status === 'OPEN')
                <form action="{{ route('hub.bagging.seal', $bag->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 bg-yellow-500 text-gray-900 font-bold rounded-xl shadow-sm hover:bg-yellow-600 transition text-sm">
                        <i class="fa-solid fa-lock mr-1"></i> Seal Bag
                    </button>
                </form>
            @elseif($bag->status === 'SEALED')
                <form action="{{ route('hub.bagging.manifest', $bag->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white font-bold rounded-xl shadow-sm hover:bg-black transition text-sm">
                        <i class="fa-solid fa-file-invoice mr-1"></i> Create Manifest
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                <h4 class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-4">Bag Details</h4>
                <div class="space-y-3 text-sm">
                    <div>
                        <div class="text-gray-500 text-xs">Destination</div>
                        <div class="font-bold text-gray-900">{{ $bag->destination }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500 text-xs">Shipment Count</div>
                        <div class="font-bold text-gray-900">{{ $bag->shipments->count() }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500 text-xs">Total Weight</div>
                        <div class="font-bold text-gray-900">{{ $bag->weight_kg ?? '0' }} kg</div>
                    </div>
                    <div>
                        <div class="text-gray-500 text-xs">Created At</div>
                        <div class="font-bold text-gray-900">{{ $bag->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>

            @if($bag->status === 'OPEN')
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-4 bg-gray-50 border-b border-gray-200">
                    <h4 class="font-bold text-gray-800"><i class="fa-solid fa-barcode mr-2"></i> Add Shipment</h4>
                </div>
                <div class="p-5">
                    <form action="{{ route('hub.bagging.add_shipment', $bag->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Scan / Enter AWB</label>
                            <input type="text" name="awb_number" autofocus required class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl font-bold tracking-widest focus:border-[var(--gold)] outline-none placeholder-gray-300" placeholder="AWB...">
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-gray-900 text-white font-bold rounded-xl text-sm hover:bg-black transition">Add to Bag</button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        <div class="md:col-span-3 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <h4 class="font-bold text-gray-800">Shipments in Bag</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-gray-50 text-[10px] font-extrabold uppercase text-gray-500 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3">AWB</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Destination</th>
                            <th class="px-4 py-3">Weight</th>
                            <th class="px-4 py-3">Status</th>
                            @if($bag->status === 'OPEN')
                            <th class="px-4 py-3 text-right">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium">
                        @forelse($bag->shipments as $shipment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-bold text-gray-900">{{ $shipment->awb_number }}</td>
                            <td class="px-4 py-3">{{ $shipment->receiver_name }}</td>
                            <td class="px-4 py-3">{{ $shipment->delivery_city }} ({{ $shipment->delivery_pincode }})</td>
                            <td class="px-4 py-3">{{ $shipment->weight_kg }} kg</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded bg-gray-100 text-gray-600 text-[10px] font-bold">{{ $shipment->status }}</span>
                            </td>
                            @if($bag->status === 'OPEN')
                            <td class="px-4 py-3 text-right">
                                <form action="{{ route('hub.bagging.remove_shipment', [$bag->id, $shipment->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-xs"><i class="fa-solid fa-trash"></i> Remove</button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr><td colspan="{{ $bag->status === 'OPEN' ? '6' : '5' }}" class="px-4 py-12 text-center text-gray-400">No shipments in this bag yet. Scan AWB to add.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


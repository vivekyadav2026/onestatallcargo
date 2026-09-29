@extends('layouts.admin')
@section('title', 'Pincode Serviceability - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Pincode Mapping</h1>
            <p class="text-sm text-gray-500 mt-1">Assign Pincodes to Franchises/Hubs for Internal Routing.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 font-bold rounded-xl border border-green-200">
            <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="p-4 bg-red-50 text-red-700 font-bold rounded-xl border border-red-200">
            @foreach($errors->all() as $error)
                <p><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Manual Entry -->
        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm">
            <h2 class="font-bold text-gray-900 mb-4 border-b pb-2"><i class="fa-solid fa-keyboard mr-2"></i> Manual Assign</h2>
            <form action="{{ route('admin.pincodes.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Select Franchise</label>
                    <select name="franchise_id" required class="w-full px-4 py-2 border border-gray-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                        <option value="">Select an Approved Franchise...</option>
                        @foreach($franchises as $franchise)
                            <option value="{{ $franchise->id }}">{{ $franchise->user?->company_name ?? $franchise->user?->name ?? 'Unknown Franchise' }} ({{ $franchise->city }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Pincodes (Comma Separated)</label>
                    <textarea name="pincodes" rows="3" placeholder="e.g. 400001, 400002, 400003" required class="w-full px-4 py-2 border border-gray-200 rounded-xl outline-none focus:border-blue-500 text-sm"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">City (Optional)</label>
                        <input type="text" name="city" class="w-full px-4 py-2 border border-gray-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">State (Optional)</label>
                        <input type="text" name="state" class="w-full px-4 py-2 border border-gray-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                    </div>
                </div>
                <button type="submit" class="w-full py-2.5 bg-[#1e293b] text-white rounded-xl font-bold shadow-md hover:bg-black transition text-sm">
                    Map Pincodes
                </button>
            </form>
        </div>

        <!-- Bulk Upload -->
        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm">
            <h2 class="font-bold text-gray-900 mb-4 border-b pb-2"><i class="fa-solid fa-file-csv mr-2"></i> Bulk Excel/CSV Upload</h2>
            <form action="{{ route('admin.pincodes.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Select Franchise</label>
                    <select name="franchise_id" required class="w-full px-4 py-2 border border-gray-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                        <option value="">Select an Approved Franchise...</option>
                        @foreach($franchises as $franchise)
                            <option value="{{ $franchise->id }}">{{ $franchise->user?->company_name ?? $franchise->user?->name ?? 'Unknown Franchise' }} ({{ $franchise->city }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Upload File (.csv)</label>
                    <input type="file" name="import_file" accept=".csv,.txt" required class="w-full px-4 py-2 border border-dashed border-gray-300 rounded-xl outline-none text-sm bg-gray-50">
                    <p class="text-[10px] text-gray-400 mt-2">Format: Column 1 (Pincode), Column 2 (City), Column 3 (State). First row is skipped as header.</p>
                </div>
                <button type="submit" class="w-full py-2.5 bg-[#FFD700] text-gray-900 rounded-xl font-extrabold shadow-md hover:bg-yellow-400 transition text-sm">
                    <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Upload & Map
                </button>
            </form>
            
            <div class="mt-4 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                <div class="text-xs font-bold text-blue-800 mb-1"><i class="fa-solid fa-lightbulb mr-1"></i> How Routing Works:</div>
                <p class="text-[10px] text-blue-700">When a seller books an order, the system checks this table first. If the delivery pincode exists here, it is automatically assigned to that Franchise\'s Hub. If not, it falls back to 3rd-Party Couriers (Delhivery).</p>
            </div>
        </div>
    </div>

    <!-- Pincode Directory List -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden mt-6">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-800">Mapped Serviceable Area Directory</h3>
            <form action="{{ route('admin.pincodes.index') }}" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Pincode or City..." class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs outline-none focus:border-blue-500">
                <button type="submit" class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-lg text-xs font-bold hover:bg-gray-300">Search</button>
            </form>
        </div>
        
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-100 text-[10px] font-extrabold uppercase tracking-wider text-gray-500">
                        <th class="px-6 py-3">Pincode</th>
                        <th class="px-6 py-3">City / State</th>
                        <th class="px-6 py-3">Assigned Franchise</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($pincodes as $pin)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3 font-bold text-lg text-gray-900">{{ $pin->pincode }}</td>
                        <td class="px-6 py-3">
                            <div class="font-bold">{{ $pin->city }}</div>
                            <div class="text-[10px] text-gray-400">{{ $pin->state }}</div>
                        </td>
                        <td class="px-6 py-3">
                            <div class="font-bold text-blue-700">{{ $pin->franchise?->user?->company_name ?? $pin->franchise?->user?->name ?? 'Central Network / Unassigned' }}</div>
                            <div class="text-[10px] text-gray-500">Franchise ID: {{ $pin->franchise_id ? '#' . $pin->franchise_id : 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-3">
                            @if($pin->is_active)
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-[10px] font-bold uppercase">Active</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-[10px] font-bold uppercase">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-right">
                            <form action="{{ route('admin.pincodes.destroy', $pin->id) }}" method="POST" onsubmit="return confirm('Remove this pincode mapping?');">
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-red-700 transition" title="Delete Mapping"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                            <i class="fa-solid fa-map-location-dot text-3xl mb-2 text-gray-300"></i>
                            <p class="text-sm font-bold">No mapped pincodes found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pincodes->hasPages())
            <div class="px-6 py-3 border-t border-gray-100">
                {{ $pincodes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

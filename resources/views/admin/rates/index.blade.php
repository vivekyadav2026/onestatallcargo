@extends('layouts.admin')

@section('title', 'Rate Engine - OneStall Cargo')

@section('content')
<div class="space-y-6" x-data="{ showAddModal: false, showEditModal: false, editData: {} }">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Rate Engine Configuration</h1>
            <p class="text-sm text-gray-500 mt-1">Configure volumetric and dead weight pricing per zone (500g logic)</p>
        </div>
        <div class="flex gap-2">
            <button @click="showAddModal = true" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[var(--gold)] text-gray-900 shadow-md hover:bg-[var(--gold-deep)] transition-colors"><i class="fa-solid fa-plus"></i> Add New Rule</button>
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
                        <th class="px-6 py-4">Zone Type</th><th class="px-6 py-4">Courier Partner</th>
                        <th class="px-6 py-4">Base Rate (first 500g)</th>
                        <th class="px-6 py-4">Additional Rate (per 500g)</th>
                        <th class="px-6 py-4">RTO Surcharge</th>
                        <th class="px-6 py-4">COD Surcharge</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700 font-medium">
                    @forelse($rates as $rate)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-900 uppercase">{{ $rate->zone_type }}</span>
                            </td>
                            <td class="px-6 py-4 text-[var(--gold-deep)] font-bold">₹{{ number_format($rate->base_rate, 2) }}</td>
                            <td class="px-6 py-4 text-gray-900 font-bold">₹{{ number_format($rate->additional_weight_rate, 2) }}</td>
                            <td class="px-6 py-4 text-red-500">₹{{ number_format($rate->rto_surcharge, 2) }}</td>
                            <td class="px-6 py-4 text-gray-900">₹{{ number_format($rate->cod_surcharge, 2) }}</td>
                            <td class="px-6 py-4 text-right">
                                <button @click="editData = { id: {{ $rate->id }}, zone_type: '{{ $rate->zone_type }}', base_rate: {{ $rate->base_rate }}, additional: {{ $rate->additional_weight_rate }}, rto: {{ $rate->rto_surcharge }}, cod: {{ $rate->cod_surcharge }} }; showEditModal = true" class="text-gray-500 hover:text-gray-900 font-bold text-xs"><i class="fa-solid fa-pen"></i> Edit</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <p>No rate configurations found. System default is being used.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Rule Modal -->
    <div x-show="showAddModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-10 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showAddModal = false">
                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
            </div>
            
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('admin.rates.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-xl leading-6 font-extrabold text-gray-900 mb-6 border-b border-gray-100 pb-4">Add Rate Zone</h3>
                        
                        <div class="space-y-4">
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-700 mb-1">Apply to Specific Courier (Optional)</label>
                                <select name="courier_id" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none" x-model="editData.courier_id">
                                    <option value="">-- Apply to All --</option>
                                    @foreach($couriers as $courier)
                                        <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Zone Type (e.g. Local)</label>
                                    <input type="text" name="zone_type" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none uppercase">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Specific Courier (Optional)</label>
                                    <select name="courier_id" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                                        <option value="">-- Apply to All --</option>
                                        @foreach($couriers as $courier)
                                            <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Base Rate (0-500g)</label>
                                    <input type="number" step="0.01" name="base_rate" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Additional 500g Rate</label>
                                    <input type="number" step="0.01" name="additional_weight_rate" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">RTO Surcharge</label>
                                    <input type="number" step="0.01" name="rto_surcharge" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">COD Handling Fee</label>
                                    <input type="number" step="0.01" name="cod_surcharge" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 gap-2">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-2.5 bg-gray-900 text-base font-bold text-white hover:bg-black sm:w-auto sm:text-sm">
                            Add Zone
                        </button>
                        <button type="button" @click="showAddModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Rule Modal -->
    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-10 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showEditModal = false">
                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
            </div>
            
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <!-- Action bound dynamically to Alpine editData.id -->
                <form :action="'{{ url('admin/rates') }}/' + editData.id" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-xl leading-6 font-extrabold text-gray-900 mb-6 border-b border-gray-100 pb-4">Edit Rate: <span x-text="editData.zone_type" class="uppercase"></span></h3>
                        
                        <div class="space-y-4">
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-700 mb-1">Apply to Specific Courier (Optional)</label>
                                <select name="courier_id" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none" x-model="editData.courier_id">
                                    <option value="">-- Apply to All --</option>
                                    @foreach($couriers as $courier)
                                        <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Base Rate (0-500g)</label>
                                    <input type="number" step="0.01" name="base_rate" x-model="editData.base_rate" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Additional 500g Rate</label>
                                    <input type="number" step="0.01" name="additional_weight_rate" x-model="editData.additional" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">RTO Surcharge</label>
                                    <input type="number" step="0.01" name="rto_surcharge" x-model="editData.rto" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">COD Handling Fee</label>
                                    <input type="number" step="0.01" name="cod_surcharge" x-model="editData.cod" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 gap-2">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-2.5 bg-gray-900 text-base font-bold text-white hover:bg-black sm:w-auto sm:text-sm">
                            Save Changes
                        </button>
                        <button type="button" @click="showEditModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@extends('layouts.seller')
@section('title', 'Weight Freeze - OneStall Cargo')

@section('content')
<div class="space-y-6" x-data="{ activeTab: '{{ $tab ?? 'all' }}', showModal: false, showImportModal: false, showViewModal: false, selectedFreeze: null }">
    
    <!-- Top Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Weight Freeze</h1>
            <p class="text-sm text-gray-500 font-medium mt-1">Set and manage product dimensions & weight for accurate shipping charges.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('seller.weight.freeze.export') }}" class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-xs font-bold hover:bg-gray-50 flex items-center gap-2 shadow-sm transition"><i class="fa-solid fa-download"></i> Export</a>
            <button @click="showImportModal = true" class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-xs font-bold hover:bg-gray-50 flex items-center gap-2 shadow-sm transition"><i class="fa-solid fa-upload"></i> Import</button>
            <button @click="showModal = true" class="bg-[#0f172a] text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-[#1e293b] flex items-center gap-2 shadow-sm transition"><i class="fa-solid fa-asterisk"></i> Add Weight Freeze</button>
        </div>
    </div>

    <!-- Main Container -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        
        <!-- Status Tabs -->
        <div class="flex justify-between items-center border-b border-gray-100 pr-4">
            <div class="flex px-4 pt-2 overflow-x-auto whitespace-nowrap">
                <button @click="window.location.href='?tab=all'" :class="activeTab === 'all' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-5 py-3 text-xs transition">All</button>
                <button @click="window.location.href='?tab=requested'" :class="activeTab === 'requested' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-5 py-3 text-xs transition">Requested</button>
                <button @click="window.location.href='?tab=accepted'" :class="activeTab === 'accepted' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-5 py-3 text-xs transition">Accepted</button>
                <button @click="window.location.href='?tab=rejected'" :class="activeTab === 'rejected' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-5 py-3 text-xs transition">Rejected</button>
            </div>
            <div>
                <form action="{{ route('seller.weight.freeze') }}" method="GET" class="relative">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by SKU..." class="pl-8 pr-3 py-1.5 border border-gray-200 rounded-lg text-xs w-48 outline-none focus:border-[#4338ca]">
                </form>
            </div>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50/80 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <th class="px-4 py-3">Product Details</th>
                        <th class="px-4 py-3 text-center">Quantity</th>
                        <th class="px-4 py-3 text-center">Dimensions (LBH) in CM</th>
                        <th class="px-4 py-3 text-center">Weight (KG)</th>
                        <th class="px-4 py-3 text-center">Created Date</th>
                        <th class="px-4 py-3 text-center">Freeze Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium text-gray-700">
                    @forelse($freezes as $freeze)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-900">{{ $freeze->product_name }}</div>
                                <div class="text-[10px] text-gray-400">SKU: {{ $freeze->sku ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-3 text-center font-bold">1</td>
                            <td class="px-4 py-3 text-center font-mono">{{ $freeze->length }} x {{ $freeze->width }} x {{ $freeze->height }}</td>
                            <td class="px-4 py-3 text-center font-bold text-blue-600">{{ $freeze->weight }} kg</td>
                            <td class="px-4 py-3 text-center text-gray-500">{{ $freeze->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($freeze->status === 'requested')
                                    <span class="px-2 py-1 bg-amber-50 text-amber-600 rounded font-bold uppercase text-[9px]">Requested</span>
                                @elseif($freeze->status === 'accepted')
                                    <span class="px-2 py-1 bg-green-50 text-green-600 rounded font-bold uppercase text-[9px]">Accepted</span>
                                @else
                                    <span class="px-2 py-1 bg-red-50 text-red-600 rounded font-bold uppercase text-[9px]">Rejected</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button @click="selectedFreeze = {{ $freeze->toJson() }}; showViewModal = true" class="text-blue-600 hover:text-blue-800 font-bold bg-blue-50 px-3 py-1 rounded-md transition">View</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-24 text-center">
                                <i class="fa-solid fa-asterisk text-4xl text-blue-100 mb-4 block"></i>
                                <p class="text-sm font-bold text-gray-400">No records found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($freezes->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-[#f8fafc]">
                {{ $freezes->links() }}
            </div>
        @endif
    </div>

    <!-- Improved Add Weight Freeze Modal -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm transition-opacity" @click="showModal = false" aria-hidden="true"></div>
            
            <div x-show="showModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-[550px] sm:w-full border border-gray-100">
                
                <form action="{{ route('seller.weight.freeze.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-6 pt-6 pb-6">
                        <div class="flex justify-between items-start mb-5">
                            <div>
                                <h3 class="text-[17px] font-extrabold text-gray-900" id="modal-title">Add Weight Freeze</h3>
                                <p class="text-[13px] text-gray-500 mt-0.5">Set product dimensions & weight to freeze shipping charges.</p>
                            </div>
                            <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition bg-gray-50 hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center">
                                <i class="fa-solid fa-times text-sm"></i>
                            </button>
                        </div>
                        
                        <div class="space-y-5">
                            <!-- Product Name & SKU -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">Product Details</label>
                                <div class="flex gap-3">
                                    <div class="relative w-full">
                                        <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                        <input type="text" name="product_name" required placeholder="Search by product name or SKU..." class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition shadow-sm placeholder:text-gray-400">
                                    </div>
                                    <input type="text" name="sku" placeholder="SKU" class="w-1/3 py-2 px-3 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition shadow-sm placeholder:text-gray-400">
                                </div>
                            </div>
                            
                            <!-- Dimensions -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">Dimensions (cm)</label>
                                <div class="flex gap-3">
                                    <input type="number" step="0.1" name="length" required placeholder="Length" class="w-1/3 py-2 px-3 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition shadow-sm placeholder:text-gray-400">
                                    <input type="number" step="0.1" name="width" required placeholder="Breadth" class="w-1/3 py-2 px-3 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition shadow-sm placeholder:text-gray-400">
                                    <input type="number" step="0.1" name="height" required placeholder="Height" class="w-1/3 py-2 px-3 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition shadow-sm placeholder:text-gray-400">
                                </div>
                            </div>
                            
                            <!-- Quantity & Weight -->
                            <div class="flex gap-4">
                                <div class="w-1/2">
                                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">Quantity</label>
                                    <input type="number" value="1" min="1" disabled class="w-full py-2 px-3 border border-gray-200 bg-gray-50 rounded-lg text-sm text-gray-500 shadow-sm cursor-not-allowed">
                                </div>
                                <div class="w-1/2">
                                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">Weight (kg)</label>
                                    <input type="number" step="0.001" name="weight" required placeholder="0.000" class="w-full py-2 px-3 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition shadow-sm placeholder:text-gray-400">
                                </div>
                            </div>
                            
                            <!-- Proof Images -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">Proof Images <span class="text-gray-400 font-normal lowercase tracking-normal">(max 5 &middot; up to 5 MB each)</span></label>
                                <div class="mt-1 flex justify-center px-6 py-8 border-2 border-dashed border-gray-200 rounded-xl hover:border-blue-400 hover:bg-blue-50/50 transition cursor-pointer relative group" onclick="document.getElementById('proof_images').click()">
                                    <div class="space-y-2 text-center">
                                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto group-hover:bg-blue-100 transition">
                                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                        </div>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <span class="text-blue-600 font-bold">Click to add images</span>
                                        </div>
                                    </div>
                                    <input id="proof_images" name="proof_images[]" type="file" multiple accept="image/*" class="sr-only">
                                </div>
                                <p class="text-[11px] font-semibold text-gray-500 mt-2 text-center" id="file_count">No files selected</p>
                                <script>
                                    document.getElementById('proof_images').addEventListener('change', function(e) {
                                        let count = e.target.files.length;
                                        document.getElementById('file_count').textContent = count > 0 ? count + " files selected" : "No files selected";
                                        document.getElementById('file_count').className = count > 0 ? "text-[11px] font-semibold text-blue-600 mt-2 text-center" : "text-[11px] font-semibold text-gray-500 mt-2 text-center";
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white border-t border-gray-100 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-2xl">
                        <button type="submit" class="inline-flex justify-center rounded-lg px-6 py-2.5 bg-[#0f172a] text-sm font-bold text-white hover:bg-[#1e293b] shadow-sm transition items-center gap-2">
                            <i class="fa-solid fa-asterisk text-[10px]"></i> Add Freeze
                        </button>
                        <button type="button" @click="showModal = false" class="inline-flex justify-center rounded-lg border border-gray-200 px-6 py-2.5 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Improved Import Modal -->
    <div x-show="showImportModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div x-show="showImportModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm transition-opacity" @click="showImportModal = false" aria-hidden="true"></div>
            
            <div x-show="showImportModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100">
                <form action="{{ route('seller.weight.freeze.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-6 pt-6 pb-6">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="text-[17px] font-extrabold text-gray-900" id="modal-title">Import Weight Freezes</h3>
                            <button type="button" @click="showImportModal = false" class="text-gray-400 hover:text-gray-600 transition bg-gray-50 hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center"><i class="fa-solid fa-times text-sm"></i></button>
                        </div>
                        <p class="text-[13px] text-gray-500 mb-5 leading-relaxed">Upload a CSV file containing your product dimensions and weights. Columns required: <strong class="text-gray-700">Product Name, SKU, Length, Width, Height, Weight</strong>.</p>
                        <div class="mt-1 flex justify-center px-6 py-8 border-2 border-dashed border-gray-200 rounded-xl hover:border-blue-400 hover:bg-blue-50/50 transition cursor-pointer relative group">
                            <input type="file" name="import_file" required accept=".csv,.txt" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        </div>
                    </div>
                    <div class="bg-white border-t border-gray-100 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-2xl">
                        <button type="submit" class="inline-flex justify-center rounded-lg px-6 py-2.5 bg-blue-600 text-sm font-bold text-white hover:bg-blue-700 shadow-sm transition">Import CSV</button>
                        <button type="button" @click="showImportModal = false" class="inline-flex justify-center rounded-lg border border-gray-200 px-6 py-2.5 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm transition">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Improved View Freeze Modal -->
    <div x-show="showViewModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div x-show="showViewModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm transition-opacity" @click="showViewModal = false" aria-hidden="true"></div>
            
            <div x-show="showViewModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100">
                <div class="bg-white px-6 pt-6 pb-6">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-[17px] font-extrabold text-gray-900" id="modal-title">Weight Freeze Details</h3>
                        <button type="button" @click="showViewModal = false" class="text-gray-400 hover:text-gray-600 transition bg-gray-50 hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center"><i class="fa-solid fa-times text-sm"></i></button>
                    </div>
                    <div class="space-y-4" x-show="selectedFreeze">
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <strong class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block mb-1">Product Details</strong>
                            <span class="text-sm font-extrabold text-gray-900 block" x-text="selectedFreeze?.product_name"></span>
                            <span class="text-[11px] text-gray-500 font-medium block mt-0.5">SKU: <span x-text="selectedFreeze?.sku"></span></span>
                        </div>
                        
                        <div class="flex gap-4">
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 w-1/2">
                                <strong class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block mb-1">Dimensions</strong> 
                                <span class="text-sm font-mono font-bold text-gray-900 block"><span x-text="selectedFreeze?.length"></span> x <span x-text="selectedFreeze?.width"></span> x <span x-text="selectedFreeze?.height"></span> cm</span>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 w-1/2">
                                <strong class="text-[10px] font-bold text-blue-400 uppercase tracking-widest block mb-1">Weight</strong> 
                                <span class="text-lg font-black text-blue-700 block leading-none"><span x-text="selectedFreeze?.weight"></span> kg</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <strong class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block">Status</strong> 
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase bg-white border shadow-sm block" 
                                  :class="{
                                      'border-amber-200 text-amber-600': selectedFreeze?.status === 'requested',
                                      'border-green-200 text-green-600': selectedFreeze?.status === 'accepted',
                                      'border-red-200 text-red-600': selectedFreeze?.status === 'rejected'
                                  }"
                                  x-text="selectedFreeze?.status"></span>
                        </div>
                    </div>
                </div>
                <div class="bg-white border-t border-gray-100 px-6 py-4 flex flex-row-reverse rounded-b-2xl">
                    <button type="button" @click="showViewModal = false" class="inline-flex justify-center rounded-lg border border-gray-200 px-6 py-2.5 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm transition">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection


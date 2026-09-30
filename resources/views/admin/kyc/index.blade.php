@extends('layouts.admin')

@section('title', 'KYC Approvals - OneStall Cargo Admin')

@section('content')
<div class="space-y-6" x-data="{ showRejectModal: false, rejectUrl: '', docModal: false, docUrl: '', detailsModal: false, kycDetails: null }">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">KYC Verification Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Review and verify seller business documents (Aadhaar, PAN, GST)</p>
        </div>
        
        <!-- Filter Tabs -->
        <div class="flex bg-gray-100 p-1 rounded-xl">
            <a href="{{ route('admin.kyc.index', ['status' => 'all']) }}" class="px-4 py-2 text-xs font-bold rounded-lg transition {{ request('status') === 'all' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-900' }}">All Requests</a>
            <a href="{{ route('admin.kyc.index', ['status' => 'pending']) }}" class="px-4 py-2 text-xs font-bold rounded-lg transition {{ request('status', 'pending') === 'pending' ? 'bg-white shadow text-yellow-700' : 'text-gray-500 hover:text-gray-900' }}">Pending</a>
            <a href="{{ route('admin.kyc.index', ['status' => 'approved']) }}" class="px-4 py-2 text-xs font-bold rounded-lg transition {{ request('status') === 'approved' ? 'bg-white shadow text-green-700' : 'text-gray-500 hover:text-gray-900' }}">Approved</a>
            <a href="{{ route('admin.kyc.index', ['status' => 'rejected']) }}" class="px-4 py-2 text-xs font-bold rounded-lg transition {{ request('status') === 'rejected' ? 'bg-white shadow text-red-700' : 'text-gray-500 hover:text-gray-900' }}">Rejected</a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-green-50 text-green-700 border border-green-200 font-bold text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Data Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <th class="px-6 py-4">Seller Details</th>
                        <th class="px-6 py-4">Document Details</th>
                        <th class="px-6 py-4">Uploaded Documents</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700 font-medium">
                    @forelse($kycs as $kyc)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $kyc->user->name }}</div>
                                <div class="text-[11px] text-gray-500">{{ $kyc->user->email }}</div>
                                <div class="text-[10px] text-gray-400 font-mono mt-0.5">ID: #{{ $kyc->user->id }}</div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-xs font-bold text-gray-800">{{ $kyc->business_type }}</div>
                                <div class="text-xs text-gray-600 mt-1"><span class="font-semibold text-gray-500">{{ $kyc->document_type }}:</span> {{ $kyc->document_number }}</div>
                                <div class="text-xs font-mono text-gray-600"><span class="font-semibold text-gray-500">PAN:</span> {{ $kyc->pan_number }}</div>
                                @if($kyc->gst_number)
                                    <div class="text-xs font-mono text-gray-600"><span class="font-semibold text-gray-500">GST:</span> {{ $kyc->gst_number }}</div>
                                @endif
                                @if($kyc->user->account_number)
                                    <div class="mt-2 pt-2 border-t border-gray-100">
                                        <div class="text-[10px] font-bold text-gray-400 uppercase">Bank Details</div>
                                        <div class="text-[11px] font-bold text-gray-800">{{ $kyc->user->bank_name }}</div>
                                        <div class="text-xs font-mono text-gray-600">{{ $kyc->user->account_number }} ({{ $kyc->user->ifsc_code }})</div>
                                        <div class="text-[10px] text-gray-500">{{ $kyc->user->account_holder_name }}</div>
                                    </div>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @if($kyc->id_front_path)
                                        <button @click="docUrl = '{{ asset('storage/' . $kyc->id_front_path) }}'; docModal = true" class="px-2 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded text-[10px] font-bold hover:bg-blue-100">
                                            <i class="fa-solid fa-file-image mr-1"></i> ID Front
                                        </button>
                                    @endif
                                    @if($kyc->id_back_path)
                                        <button @click="docUrl = '{{ asset('storage/' . $kyc->id_back_path) }}'; docModal = true" class="px-2 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded text-[10px] font-bold hover:bg-blue-100">
                                            <i class="fa-solid fa-file-image mr-1"></i> ID Back
                                        </button>
                                    @endif
                                    @if($kyc->pan_doc_path)
                                        <button @click="docUrl = '{{ asset('storage/' . $kyc->pan_doc_path) }}'; docModal = true" class="px-2 py-1 bg-purple-50 text-purple-700 border border-purple-200 rounded text-[10px] font-bold hover:bg-purple-100">
                                            <i class="fa-solid fa-file-image mr-1"></i> PAN Doc
                                        </button>
                                    @endif
                                    @if($kyc->gst_doc_path)
                                        <button @click="docUrl = '{{ asset('storage/' . $kyc->gst_doc_path) }}'; docModal = true" class="px-2 py-1 bg-green-50 text-green-700 border border-green-200 rounded text-[10px] font-bold hover:bg-green-100">
                                            <i class="fa-solid fa-file-image mr-1"></i> GST Doc
                                        </button>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                @if($kyc->status === 'approved')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold"><i class="fa-solid fa-check mr-1"></i> Approved</span>
                                @elseif($kyc->status === 'pending')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold"><i class="fa-solid fa-clock mr-1"></i> Pending</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold"><i class="fa-solid fa-xmark mr-1"></i> Rejected</span>
                                    @if($kyc->rejection_reason)
                                        <div class="text-[10px] text-red-600 mt-1 max-w-xs truncate" title="{{ $kyc->rejection_reason }}">{{ $kyc->rejection_reason }}</div>
                                    @endif
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right space-x-2">
                                @php
                                      $kycData = [
                                          'name' => $kyc->user->name,
                                          'email' => $kyc->user->email,
                                          'phone' => $kyc->user->phone,
                                          'company' => $kyc->user->company_name,
                                          'business_type' => $kyc->business_type,
                                          'document_type' => $kyc->document_type,
                                          'document_number' => $kyc->document_number,
                                          'pan' => $kyc->pan_number,
                                          'gst' => $kyc->gst_number,
                                          'bank_name' => $kyc->user->bank_name,
                                          'account_number' => $kyc->user->account_number,
                                          'ifsc' => $kyc->user->ifsc_code,
                                          'account_holder' => $kyc->user->account_holder_name,
                                          'id_front' => $kyc->id_front_path ? asset('storage/' . $kyc->id_front_path) : null,
                                          'id_back' => $kyc->id_back_path ? asset('storage/' . $kyc->id_back_path) : null,
                                          'pan_doc' => $kyc->pan_doc_path ? asset('storage/' . $kyc->pan_doc_path) : null,
                                          'gst_doc' => $kyc->gst_doc_path ? asset('storage/' . $kyc->gst_doc_path) : null,
                                          'status' => $kyc->status
                                      ];
                                  @endphp
                                  <button @click="kycDetails = JSON.parse(decodeURIComponent('{{ rawurlencode(json_encode($kycData)) }}')); detailsModal = true" type="button" class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg text-xs font-bold hover:bg-blue-200 transition mb-1">
                                      <i class="fa-solid fa-eye mr-1"></i> Details
                                  </button>
                                  
                                  @if($kyc->status !== 'approved')
                                    <form action="{{ route('admin.kyc.approve', $kyc->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Approve KYC for {{ addslashes($kyc->user->name) }}?')" class="px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs font-bold hover:bg-green-700 transition">
                                            <i class="fa-solid fa-check mr-1"></i> Approve
                                        </button>
                                    </form>
                                @endif

                                @if($kyc->status !== 'rejected')
                                    <button @click="rejectUrl = '{{ route('admin.kyc.reject', $kyc->id) }}'; showRejectModal = true" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-bold hover:bg-red-200 transition">
                                        <i class="fa-solid fa-xmark mr-1"></i> Reject
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-shield text-3xl mb-2"></i>
                                <p>No KYC verification requests found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kycs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $kycs->links() }}
            </div>
        @endif
    </div>

    <!-- Rejection Modal -->
    <div x-show="showRejectModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <div class="fixed inset-0 bg-gray-900 opacity-75" @click="showRejectModal = false"></div>
            
            <div class="inline-block bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all max-w-md w-full p-6 relative z-10">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Reject KYC Verification</h3>
                <p class="text-xs text-gray-500 mb-4">Specify the reason for rejection so the seller can re-upload correct documents.</p>
                
                <form :action="rejectUrl" method="POST">
                    @csrf
                    <textarea name="rejection_reason" rows="3" required placeholder="e.g. Blurred PAN card image or mismatched Aadhaar name" class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:outline-none focus:border-red-500 mb-4"></textarea>
                    
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showRejectModal = false" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl text-xs font-bold">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded-xl text-xs font-bold hover:bg-red-700">Confirm Rejection</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Document Preview Modal -->
    <div x-show="docModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <div class="fixed inset-0 bg-gray-900 opacity-80" @click="docModal = false"></div>
            
            <div class="inline-block bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all max-w-2xl w-full p-4 relative z-10">
                <div class="flex justify-between items-center mb-3 px-2">
                    <h4 class="font-bold text-gray-900 text-sm">Document Preview</h4>
                    <button @click="docModal = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
                </div>
                <div class="bg-gray-100 rounded-xl overflow-hidden flex items-center justify-center max-h-[70vh] p-2">
                    <img :src="docUrl" class="max-h-[65vh] object-contain rounded-lg" alt="Document Preview">
                </div>
            </div>
        </div>
    </div>

    <!-- Full Details Modal -->
    <div x-show="detailsModal" style="display: none;" class="fixed inset-0 z-[60] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <div class="fixed inset-0 bg-gray-900 opacity-80" @click="detailsModal = false"></div>
            
            <div class="inline-block bg-white rounded-2xl text-left shadow-2xl transform transition-all max-w-4xl w-full relative z-[70] my-8 flex flex-col max-h-[90vh]">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 shrink-0">
                    <h3 class="font-extrabold text-gray-900 text-lg flex items-center gap-2">
                        <i class="fa-solid fa-address-card text-[#0f172a]"></i> Seller KYC & Profile Details
                    </h3>
                    <button @click="detailsModal = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl"></i></button>
                </div>
                
                <div class="p-6 overflow-y-auto flex-1" x-show="kycDetails">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Personal & Company Details -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 border-b border-gray-200 pb-2">Profile & Business</h4>
                            <div class="space-y-3">
                                <div><span class="text-[11px] text-gray-500 block">Seller Name</span><div class="font-bold text-gray-900 text-sm" x-text="kycDetails.name"></div></div>
                                <div><span class="text-[11px] text-gray-500 block">Email & Phone</span><div class="font-bold text-gray-900 text-sm"><span x-text="kycDetails.email"></span> | <span x-text="kycDetails.phone"></span></div></div>
                                <div><span class="text-[11px] text-gray-500 block">Company Name</span><div class="font-bold text-gray-900 text-sm" x-text="kycDetails.company || 'N/A'"></div></div>
                                <div><span class="text-[11px] text-gray-500 block">Business Type</span><div class="font-bold text-gray-900 text-sm" x-text="kycDetails.business_type"></div></div>
                            </div>
                        </div>

                        <!-- Tax & Identification -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 border-b border-gray-200 pb-2">Identification & Tax</h4>
                            <div class="space-y-3">
                                <div><span class="text-[11px] text-gray-500 block" x-text="kycDetails.document_type + ' Number'"></span><div class="font-mono font-bold text-gray-900 text-sm uppercase" x-text="kycDetails.document_number"></div></div>
                                <div><span class="text-[11px] text-gray-500 block">PAN Number</span><div class="font-mono font-bold text-gray-900 text-sm uppercase" x-text="kycDetails.pan"></div></div>
                                <div><span class="text-[11px] text-gray-500 block">GST Number</span><div class="font-mono font-bold text-gray-900 text-sm uppercase" x-text="kycDetails.gst || 'N/A'"></div></div>
                                <div><span class="text-[11px] text-gray-500 block">KYC Status</span>
                                    <div class="mt-1">
                                        <span x-show="kycDetails.status === 'approved'" class="px-2 py-1 bg-green-100 text-green-800 rounded text-[11px] font-bold"><i class="fa-solid fa-check mr-1"></i> Approved</span>
                                        <span x-show="kycDetails.status === 'pending'" class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-[11px] font-bold"><i class="fa-solid fa-clock mr-1"></i> Pending</span>
                                        <span x-show="kycDetails.status === 'rejected'" class="px-2 py-1 bg-red-100 text-red-800 rounded text-[11px] font-bold"><i class="fa-solid fa-xmark mr-1"></i> Rejected</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bank Details -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 md:col-span-2">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 border-b border-gray-200 pb-2">Bank Account Details (COD Payouts)</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div><span class="text-[11px] text-gray-500 block">Bank Name</span><div class="font-bold text-gray-900 text-sm" x-text="kycDetails.bank_name || 'N/A'"></div></div>
                                <div><span class="text-[11px] text-gray-500 block">Account Holder Name</span><div class="font-bold text-gray-900 text-sm" x-text="kycDetails.account_holder || 'N/A'"></div></div>
                                <div><span class="text-[11px] text-gray-500 block">Account Number</span><div class="font-mono font-bold text-gray-900 text-sm" x-text="kycDetails.account_number || 'N/A'"></div></div>
                                <div><span class="text-[11px] text-gray-500 block">IFSC Code</span><div class="font-mono font-bold text-gray-900 text-sm uppercase" x-text="kycDetails.ifsc || 'N/A'"></div></div>
                            </div>
                        </div>

                        <!-- Documents -->
                        <div class="md:col-span-2 mt-2">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 border-b border-gray-200 pb-2">Uploaded Documents</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <template x-if="kycDetails.id_front">
                                    <div class="border border-gray-200 rounded-lg p-2 bg-white flex flex-col items-center">
                                        <div class="text-[10px] font-bold text-gray-500 mb-2 uppercase">ID Front</div>
                                        <img :src="kycDetails.id_front" class="h-24 object-contain cursor-pointer hover:opacity-80 transition" @click="docUrl = kycDetails.id_front; docModal = true" alt="ID Front">
                                    </div>
                                </template>
                                <template x-if="kycDetails.id_back">
                                    <div class="border border-gray-200 rounded-lg p-2 bg-white flex flex-col items-center">
                                        <div class="text-[10px] font-bold text-gray-500 mb-2 uppercase">ID Back</div>
                                        <img :src="kycDetails.id_back" class="h-24 object-contain cursor-pointer hover:opacity-80 transition" @click="docUrl = kycDetails.id_back; docModal = true" alt="ID Back">
                                    </div>
                                </template>
                                <template x-if="kycDetails.pan_doc">
                                    <div class="border border-gray-200 rounded-lg p-2 bg-white flex flex-col items-center">
                                        <div class="text-[10px] font-bold text-gray-500 mb-2 uppercase">PAN Card</div>
                                        <img :src="kycDetails.pan_doc" class="h-24 object-contain cursor-pointer hover:opacity-80 transition" @click="docUrl = kycDetails.pan_doc; docModal = true" alt="PAN Card">
                                    </div>
                                </template>
                                <template x-if="kycDetails.gst_doc">
                                    <div class="border border-gray-200 rounded-lg p-2 bg-white flex flex-col items-center">
                                        <div class="text-[10px] font-bold text-gray-500 mb-2 uppercase">GST Doc</div>
                                        <img :src="kycDetails.gst_doc" class="h-24 object-contain cursor-pointer hover:opacity-80 transition" @click="docUrl = kycDetails.gst_doc; docModal = true" alt="GST">
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50 rounded-b-2xl shrink-0">
                    <button @click="detailsModal = false" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-100 transition shadow-sm">Close Window</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection





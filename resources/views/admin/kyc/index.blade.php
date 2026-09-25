@extends('layouts.admin')

@section('title', 'KYC Approvals - OneStall Cargo Admin')

@section('content')
<div class="space-y-6" x-data="{ showRejectModal: false, rejectUrl: '', docModal: false, docUrl: '' }">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">KYC Verification Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Review and verify seller business documents (Aadhaar, PAN, GST)</p>
        </div>
        
        <!-- Filter Tabs -->
        <div class="flex bg-gray-100 p-1 rounded-xl">
            <a href="{{ route('admin.kyc.index') }}" class="px-4 py-2 text-xs font-bold rounded-lg transition {{ !request('status') ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-900' }}">All Requests</a>
            <a href="{{ route('admin.kyc.index', ['status' => 'pending']) }}" class="px-4 py-2 text-xs font-bold rounded-lg transition {{ request('status') === 'pending' ? 'bg-white shadow text-yellow-700' : 'text-gray-500 hover:text-gray-900' }}">Pending</a>
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

</div>
@endsection

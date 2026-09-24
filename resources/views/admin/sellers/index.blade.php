@extends('layouts.admin')

@section('title', 'Sellers Directory - OneStall Cargo')

@section('content')
<div class="space-y-6" x-data="{ showAddModal: false, showEditModal: false, editData: {} }">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Sellers Directory</h1>
            <p class="text-sm text-gray-500 mt-1">Manage all registered B2B and B2C sellers</p>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('admin.sellers.index') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or company..." class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[var(--gold)] w-64">
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white font-bold rounded-xl text-sm hover:bg-black transition"><i class="fa-solid fa-search"></i></button>
            </form>
            <button @click="showAddModal = true" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[var(--gold)] text-gray-900 shadow-md hover:bg-[var(--gold-deep)] transition-colors"><i class="fa-solid fa-plus"></i> Add New Seller</button>
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
                        <th class="px-6 py-4">Seller Details</th>
                        <th class="px-6 py-4">Company Name</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4">Wallet Balance</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700 font-medium">
                    @forelse($sellers as $seller)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $seller->name }}</div>
                                <div class="text-[10px] text-gray-500">{{ $seller->email }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $seller->company_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $seller->phone ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-bold {{ $seller->wallet_balance < 0 ? 'text-red-500' : 'text-[var(--gold-deep)]' }}">
                                ,1{{ number_format($seller->wallet_balance, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-3">
                                <button @click="editData = { id: {{ $seller->id }}, name: '{{ addslashes($seller->name) }}', phone: '{{ addslashes($seller->phone ?? '') }}', company: '{{ addslashes($seller->company_name ?? '') }}' }; showEditModal = true" class="text-gray-500 hover:text-gray-900 font-bold text-xs"><i class="fa-solid fa-pen"></i> Edit</button>
                                
                                <a href="{{ route('admin.billing.index') }}" class="text-[var(--gold-deep)] hover:text-yellow-600 font-bold text-xs"><i class="fa-solid fa-file-invoice"></i> COD Ledger</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <p>No sellers found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($sellers->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $sellers->links() }}
            </div>
        @endif
    </div>

    <!-- Add Seller Modal -->
    <div x-show="showAddModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-10 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showAddModal = false">
                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
            </div>
            
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('admin.sellers.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-xl leading-6 font-extrabold text-gray-900 mb-6 border-b border-gray-100 pb-4">Register New Seller</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Full Name</label>
                                <input type="text" name="name" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Email Address</label>
                                <input type="email" name="email" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Company Name</label>
                                <input type="text" name="company_name" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Phone Number</label>
                                <input type="text" name="phone" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Temporary Password</label>
                                <input type="password" name="password" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 gap-2">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-2.5 bg-gray-900 text-base font-bold text-white hover:bg-black sm:w-auto sm:text-sm">
                            Create Account
                        </button>
                        <button type="button" @click="showAddModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Seller Modal -->
    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-10 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showEditModal = false">
                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
            </div>
            
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <!-- Form action is dynamic based on selected user -->
                <form :action="'{{ url('admin/sellers') }}/' + editData.id" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-xl leading-6 font-extrabold text-gray-900 mb-6 border-b border-gray-100 pb-4">Edit Seller Profile</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Full Name</label>
                                <input type="text" name="name" x-model="editData.name" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Company Name</label>
                                <input type="text" name="company_name" x-model="editData.company" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Phone Number</label>
                                <input type="text" name="phone" x-model="editData.phone" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                            </div>
                            <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl mt-4">
                                <label class="block text-xs font-bold text-blue-900 mb-1"><i class="fa-solid fa-wallet mr-1"></i> Manual Wallet Adjustment (₹)</label>
                                <p class="text-[10px] text-blue-700 mb-2">Use negative numbers to deduct, positive to add. Leave blank for no change.</p>
                                <input type="number" step="0.01" name="wallet_adjustment" placeholder="e.g. 500 or -250" class="w-full px-4 py-2 bg-white border border-blue-200 rounded-xl text-sm focus:border-blue-500 outline-none">
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

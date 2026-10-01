@extends('layouts.hub')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-extrabold text-gray-900">Manage Profile</h2>
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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Left Side: Basic Info Form -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-800 mb-6 border-b border-gray-100 pb-2">Personal Details</h3>
                
                <form action="{{ route('hub.profile.update') }}" method="POST" class="space-y-5" enctype="multipart/form-data">`n                    <div class="flex items-center gap-4 mb-4">`n                        @if($user->avatar)`n                            <img src="{{ asset('storage/' . $user->avatar) }}" class="w-16 h-16 rounded-full object-cover">`n                        @else`n                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400"><i class="fa-solid fa-user text-2xl"></i></div>`n                        @endif`n                        <div>`n                            <label class="block text-xs font-bold text-gray-700 mb-1">Profile Photo</label>`n                            <input type="file" name="avatar" accept="image/*" class="text-xs">`n                        </div>`n                    </div>
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Email Address</label>
                            <input type="email" value="{{ $user->email }}" disabled class="w-full px-4 py-2 border border-gray-200 bg-gray-50 text-gray-500 rounded-xl outline-none cursor-not-allowed">
                            <p class="text-[10px] text-gray-400 mt-1">Email cannot be changed.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-4">Security</h3>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">New Password</label>
                            <input type="password" name="password" placeholder="Leave blank to keep current password" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white font-bold rounded-xl shadow hover:bg-black transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Side: Franchise / Hub Metadata -->
        <div class="md:col-span-1 space-y-6">
            @if($franchise)
            <div class="bg-gray-900 text-white rounded-2xl shadow-sm p-6 relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-[var(--gold)] mb-4">Franchise Account</h3>
                    
                    <div class="mb-3">
                        <div class="text-[10px] text-gray-400">Company Name</div>
                        <div class="font-bold">{{ $franchise->user->company_name ?? $user->name }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-[10px] text-gray-400">City</div>
                        <div class="font-bold">{{ $franchise->city ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-gray-400">Status</div>
                        <span class="inline-block mt-1 px-2 py-1 rounded-lg text-xs font-bold {{ $franchise->status === 'approved' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                            {{ strtoupper($franchise->status) }}
                        </span>
                    </div>
                </div>
                <i class="fa-solid fa-building absolute -bottom-6 -right-6 text-8xl text-white opacity-5"></i>
            </div>
            @endif

            @if($hub)
            <div class="bg-white border border-[var(--gold)] rounded-2xl shadow-sm p-6 relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-[var(--gold-deep)] mb-4">Hub Management</h3>
                    
                    <div class="mb-3">
                        <div class="text-[10px] text-gray-500">Assigned Hub</div>
                        <div class="font-bold text-gray-900">{{ $hub->name }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-[10px] text-gray-500">Hub Code</div>
                        <div class="font-bold text-gray-900">{{ $hub->hub_code }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-gray-500">Service Area (Pincode)</div>
                        <div class="font-bold text-gray-900">{{ $hub->pincode ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('layouts.rider')
@section('content')
<div class="space-y-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-black text-gray-900">Manage Profile</h1>
        <a href="{{ route('rider.profile') }}" class="text-xs font-bold text-gray-500 hover:text-gray-900">&larr; Back</a>
    </div>

    <form action="{{ route('rider.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-3xl shadow-sm border border-gray-200 space-y-4">`n        <div class="flex items-center gap-4 mb-4">`n            @if($user->avatar)`n                <img src="{{ asset('storage/' . $user->avatar) }}" class="w-16 h-16 rounded-full object-cover">`n            @else`n                <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center text-xl"><i class="fa-solid fa-user"></i></div>`n            @endif`n            <div>`n                <label class="block text-xs font-bold text-gray-700 mb-1">Profile Photo</label>`n                <input type="file" name="avatar" accept="image/*" class="text-xs">`n            </div>`n        </div>
        @csrf
        
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Full Name</label>
            <input type="text" name="name" value="{{ $user->name }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:bg-white focus:border-[#D4AF37] outline-none transition">
        </div>
        
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Phone Number</label>
            <input type="text" name="phone" value="{{ $user->phone }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:bg-white focus:border-[#D4AF37] outline-none transition">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Email (Read Only)</label>
            <input type="email" value="{{ $user->email }}" disabled class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-sm font-bold text-gray-500 cursor-not-allowed">
        </div>
        
        <div class="pt-4 border-t border-gray-100">
            <label class="block text-xs font-bold text-gray-700 mb-1">Change Password (Optional)</label>
            <input type="password" name="password" placeholder="Leave blank to keep current" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:bg-white focus:border-[#D4AF37] outline-none transition">
        </div>
        
        <button type="submit" class="w-full py-3 mt-4 bg-[#1e293b] text-white text-sm font-black rounded-xl shadow-md hover:bg-black transition">
            Save Changes
        </button>
    </form>
</div>
@endsection

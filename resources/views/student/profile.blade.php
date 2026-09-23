@extends('layouts.student')

@section('title', 'My Profile - Testwise')

@section('content')
<div class="space-y-8">
    <div class="p-6 sm:p-8 rounded-3xl panel space-y-2">
        <h1 class="text-2xl font-black" style="color: var(--text-main) !important;">My Profile</h1>
        <p class="text-xs" style="color: var(--text-muted);">अपनी व्यक्तिगत जानकारी और पासवर्ड अपडेट करें</p>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl text-sm font-bold" style="background-color: var(--teal-soft); color: var(--teal); border: 1px solid var(--teal);">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Update Profile Form -->
        <div class="p-6 rounded-3xl panel space-y-6">
            <h3 class="text-lg font-bold" style="color: var(--text-main) !important;"><i class="fa-solid fa-user-pen" style="color: var(--gold);"></i> व्यक्तिगत जानकारी</h3>
            
            <form action="{{ route('student.profile.update') }}" method="POST" class="space-y-4 text-sm">
                @csrf
                @method('PUT')
                
                <div class="field">
                    <label>Full Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" required>
                </div>
                
                <div class="field">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ $user->email }}" required>
                </div>

                <div class="field">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="{{ $user->phone }}">
                </div>

                <div class="field">
                    <label>Study Goal</label>
                    <input type="text" name="goal" value="{{ $user->goal }}" placeholder="e.g. MP Police GD 2026 में अंतिम चयन">
                </div>

                <div class="field">
                    <label>Daily Study Goal (Minutes)</label>
                    <input type="number" name="daily_study_goal_minutes" value="{{ $user->daily_study_goal_minutes }}">
                </div>

                <button type="submit" class="w-full btn btn-gold font-bold shadow-lg" style="margin-top: 10px;">
                    प्रोफाइल अपडेट करें
                </button>
            </form>
        </div>

        <!-- Update Password Form -->
        <div class="p-6 rounded-3xl panel space-y-6 h-fit">
            <h3 class="text-lg font-bold" style="color: var(--text-main) !important;"><i class="fa-solid fa-lock" style="color: var(--gold);"></i> पासवर्ड बदलें</h3>
            
            <form action="{{ route('student.profile.password') }}" method="POST" class="space-y-4 text-sm">
                @csrf
                @method('PUT')
                
                <div class="field">
                    <label>Current Password</label>
                    <input type="password" name="current_password" required>
                    @error('current_password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                
                <div class="field">
                    <label>New Password</label>
                    <input type="password" name="new_password" required minlength="6">
                    @error('new_password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label>Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" required minlength="6">
                </div>

                <button type="submit" class="w-full btn px-4 py-3 border font-bold" style="background-color: var(--bg-main); border-color: var(--border-hard); color: var(--text-main); margin-top: 10px;">
                    पासवर्ड अपडेट करें
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

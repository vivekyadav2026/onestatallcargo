@extends('layouts.app')

@section('title', 'Reset Password - OneStall Cargo')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-12 px-4 sm:px-6">
    <div class="w-full max-w-md bg-white rounded-3xl border border-gray-100 shadow-xl p-8 sm:p-10 space-y-6">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-extrabold text-xl mx-auto shadow-sm bg-yellow-50 text-[var(--gold-deep)] border border-yellow-200">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight" style="color: #0f172a !important;">Set New Password</h1>
            <p class="text-xs text-gray-500">Choose a new password for your OneStall Cargo account</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="space-y-1">
                <label class="block text-xs font-bold text-gray-700">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $email) }}" required placeholder="you@example.com" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[var(--gold)] focus:ring-2 focus:ring-yellow-400/20 transition-all">
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-gray-700">New Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[var(--gold)] focus:ring-2 focus:ring-yellow-400/20 transition-all">
                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-gray-700">Confirm New Password</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[var(--gold)] focus:ring-2 focus:ring-yellow-400/20 transition-all">
            </div>

            <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-[var(--gold)] hover:bg-yellow-500 text-gray-900 font-extrabold text-sm shadow-md hover:shadow-lg transition-all duration-200 mt-2">
                Update Password & Sign In &rarr;
            </button>
        </form>
    </div>
</div>
@endsection


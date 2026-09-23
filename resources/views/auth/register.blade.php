@extends('layouts.app')

@section('title', 'Register - Testwise')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-12 px-4 sm:px-6">
    <div class="w-full max-w-lg bg-white rounded-3xl border border-gray-100 shadow-xl p-8 sm:p-10 space-y-6">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-extrabold text-2xl mx-auto shadow-sm bg-[var(--gold)] text-gray-900">
                T
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight" style="color: #0f172a !important;">Create Account</h1>
            <p class="text-xs text-gray-500">Join Testwise to access Webbooks, Chapter Notes & CBT Mock Tests</p>
        </div>

        <!-- Google Auth Button -->
        <div>
            <a href="{{ route('auth.google') }}" class="w-full py-3 px-4 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 font-bold text-xs text-gray-700 flex items-center justify-center gap-3 shadow-sm transition-all duration-200 hover:border-gray-300">
                <svg class="w-4 h-4" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>Sign up with Google</span>
            </a>
        </div>

        <!-- Divider -->
        <div class="relative flex items-center justify-center">
            <div class="w-full border-t border-gray-100"></div>
            <span class="bg-white px-3 text-[11px] font-bold tracking-wider text-gray-400 uppercase absolute">or</span>
        </div>

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Rahul Sharma" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[var(--gold)] focus:ring-2 focus:ring-yellow-400/20 transition-all">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="9876543210" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[var(--gold)] focus:ring-2 focus:ring-yellow-400/20 transition-all">
                    @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-gray-700">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[var(--gold)] focus:ring-2 focus:ring-yellow-400/20 transition-all">
                @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700">Password</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[var(--gold)] focus:ring-2 focus:ring-yellow-400/20 transition-all">
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" required placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[var(--gold)] focus:ring-2 focus:ring-yellow-400/20 transition-all">
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-[var(--gold)] hover:bg-yellow-500 text-gray-900 font-extrabold text-sm shadow-md hover:shadow-lg transition-all duration-200 mt-2">
                Create Account & Start Learning &rarr;
            </button>
        </form>

        <p class="text-xs text-center text-gray-500 pt-2">
            Already registered? <a href="{{ route('login') }}" class="font-bold text-gray-900 hover:underline">Log in here</a>
        </p>
    </div>
</div>
@endsection

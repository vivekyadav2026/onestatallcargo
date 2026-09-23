@extends('layouts.app')

@section('title', 'Forgot Password - OneStall Cargo')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-12 px-4 sm:px-6">
    <div class="w-full max-w-md bg-white rounded-3xl border border-gray-100 shadow-xl p-8 sm:p-10 space-y-6">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-extrabold text-xl mx-auto shadow-sm bg-yellow-50 text-[var(--gold-deep)] border border-yellow-200">
                <i class="fa-solid fa-key"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight" style="color: #0f172a !important;">Forgot Password</h1>
            <p class="text-xs text-gray-500">Enter your registered email address to receive a password reset link</p>
        </div>

        @if (session('status'))
            <div class="p-4 rounded-xl text-xs bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-start gap-2">
                <i class="fa-solid fa-circle-check mt-0.5 shrink-0"></i>
                <div>{{ session('status') }}</div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div class="space-y-1">
                <label class="block text-xs font-bold text-gray-700">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[var(--gold)] focus:ring-2 focus:ring-yellow-400/20 transition-all">
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-[var(--gold)] hover:bg-yellow-500 text-gray-900 font-extrabold text-sm shadow-md hover:shadow-lg transition-all duration-200 mt-2">
                Send Reset Link &rarr;
            </button>
        </form>

        <p class="text-xs text-center text-gray-500 pt-2">
            Remembered password? <a href="{{ route('login') }}" class="font-bold text-gray-900 hover:underline">Back to Sign In</a>
        </p>
    </div>
</div>
@endsection



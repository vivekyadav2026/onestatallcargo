@extends('layouts.app')
@section('title', $course->title_en ?? $course->title_hi . ' - Testwise')

@section('content')
<section class="py-12 md:py-20 bg-gray-50 min-h-[80vh]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <div class="bg-white rounded-3xl p-8 md:p-12 border border-gray-200 shadow-sm flex flex-col md:flex-row gap-8 items-center">
            <div class="flex-1 space-y-6">
                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase bg-red-50 text-red-600 border border-red-100">
                    {{ round((($course->price - $course->discounted_price) / $course->price) * 100) }}% OFF
                </span>
                
                <h1 class="text-3xl md:text-5xl font-extrabold text-[var(--text-main)] leading-tight">{{ $course->title_hi }}</h1>
                <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                    {{ $course->description_hi }}
                </p>
                
                <div class="pt-6 border-t border-gray-100 flex items-center gap-6">
                    <div>
                        <div class="text-3xl font-black text-[var(--text-main)] leading-none">₹{{ $course->discounted_price }}</div>
                        <div class="text-sm font-medium line-through text-gray-400 mt-1">₹{{ $course->price }}</div>
                    </div>
                    
                    <a href="{{ route('enroll', $course->id) }}" class="btn btn-gold px-8 py-3 rounded-xl shadow-lg hover:-translate-y-1 transition-transform">
                        Enroll Now <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
            
            <div class="w-full md:w-1/3 flex justify-center">
                <div class="w-48 h-48 rounded-2xl flex items-center justify-center text-7xl text-white bg-gradient-to-br from-gray-900 to-gray-800 shadow-xl transform rotate-3 hover:rotate-0 transition-transform">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-200 text-center space-y-3">
                <i class="fa-solid fa-book-open text-3xl text-[var(--gold-deep)]"></i>
                <h3 class="font-extrabold">Full Syllabus</h3>
                <p class="text-xs text-gray-500">Comprehensive study notes in PDF.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-200 text-center space-y-3">
                <i class="fa-solid fa-laptop-code text-3xl text-blue-500"></i>
                <h3 class="font-extrabold">CBT Mock Tests</h3>
                <p class="text-xs text-gray-500">Real exam-like computer-based tests.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-200 text-center space-y-3">
                <i class="fa-solid fa-chart-line text-3xl text-purple-500"></i>
                <h3 class="font-extrabold">AI Analytics</h3>
                <p class="text-xs text-gray-500">Track mistakes and weak topics.</p>
            </div>
        </div>
        
    </div>
</section>
@endsection

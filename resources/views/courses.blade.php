@extends('layouts.app')

@section('title', 'All Exam Packages - Testwise')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
</style>

<section class="py-12 md:py-16 bg-gray-50 min-h-[80vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header & Search -->
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <span class="text-xs font-extrabold uppercase tracking-widest text-[var(--theme-active)]">Explore All Exams</span>
            <h1 class="text-3xl md:text-5xl font-extrabold text-[var(--text-main)]">Find Your Target Exam</h1>
            <p class="text-gray-600 font-medium text-sm md:text-base">Browse our collection of targeted webbooks and CBT mock test series.</p>
            
            <!-- Search Form -->
            <form method="GET" action="{{ route('courses') }}" class="flex items-center gap-2 max-w-lg mx-auto pt-2">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search exam (e.g. SSC CGL, MP Police...)" class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 bg-white text-sm focus:outline-none focus:border-gray-900 shadow-sm">
                </div>
                <button type="submit" class="px-6 py-3 rounded-xl bg-gray-900 text-white font-extrabold text-sm shadow-md hover:bg-gray-800 transition-colors">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('courses') }}" class="px-4 py-3 rounded-xl bg-gray-200 text-gray-700 font-bold text-sm hover:bg-gray-300">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Courses Grid -->
        @if($courses->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($courses as $course)
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col group">
                        <div class="p-6 space-y-4 flex-1">
                            <div class="flex justify-between items-start">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl text-white bg-gradient-to-br from-gray-900 to-gray-800 group-hover:scale-110 transition-transform shadow-md">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase bg-red-50 text-red-600 border border-red-100">
                                    {{ round((($course->price - $course->discounted_price) / $course->price) * 100) }}% OFF
                                </span>
                            </div>
                            <div>
                                <h3 class="text-xl font-extrabold text-[var(--text-main)] leading-tight">{{ $course->title_hi }}</h3>
                                <p class="text-xs md:text-sm mt-2 text-gray-500 leading-relaxed">{{ $course->description_hi }}</p>
                            </div>
                            <div class="pt-4 mt-auto border-t border-gray-100">
                                <div class="flex items-end gap-2.5">
                                    <span class="text-2xl font-black text-[var(--text-main)] leading-none">₹{{ $course->discounted_price }}</span>
                                    <span class="text-sm font-medium line-through text-gray-400 mb-0.5">₹{{ $course->price }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-3.5 bg-gray-50 border-t border-gray-100">
                            <a href="{{ route('enroll', $course->id) }}" class="block w-full text-center text-sm font-bold text-gray-900 hover:text-[var(--gold-deep)] transition-colors">
                                Enroll Now &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination Links -->
            <div class="pt-6 flex justify-center">
                {{ $courses->links() }}
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-2xl border border-gray-200 p-8 max-w-md mx-auto space-y-3">
                <i class="fa-solid fa-circle-exclamation text-4xl text-gray-400"></i>
                <h3 class="text-lg font-bold text-gray-800">No exams found</h3>
                <p class="text-xs text-gray-500">No exams match your search criteria. Try searching with different keywords.</p>
                <a href="{{ route('courses') }}" class="inline-block mt-2 text-xs font-bold text-[var(--theme-active)] hover:underline">
                    View All Exams
                </a>
            </div>
        @endif
    </div>
</section>
@endsection

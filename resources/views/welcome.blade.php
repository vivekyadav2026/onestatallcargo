@extends('layouts.app')

@section('title', 'Testwise - India\'s Most Advanced Webbook Platform')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .gradient-text {
        background: linear-gradient(135deg, #17233F 0%, #D99A2B 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .hero-bg {
        background: radial-gradient(circle at top right, rgba(217, 154, 43, 0.05) 0%, transparent 30%),
                    radial-gradient(circle at bottom left, rgba(23, 35, 63, 0.03) 0%, transparent 30%);
        background-color: #F8FAFC;
    }
    
    /* Animations for the Hero Cards */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
    }
    @keyframes float-delay {
        0%, 100% { transform: translateY(0px) scale(0.95); }
        50% { transform: translateY(-10px) scale(1); }
    }
    @keyframes progress-grow {
        0% { width: 0%; }
        100% { width: 85%; }
    }
    .animate-float { animation: float 5s ease-in-out infinite; }
    .animate-float-delay { animation: float-delay 6s ease-in-out infinite 1s; }
    .animate-progress { animation: progress-grow 2s ease-out forwards 0.5s; }
</style>

<!-- Hero Section (Animated Interactive Cards) -->
<section class="hero-bg relative py-12 border-b border-gray-200/60 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col md:flex-row items-center gap-10">
        
        <!-- Left Content -->
        <div class="flex-1 space-y-5 text-center md:text-left z-20">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white shadow-sm border border-gray-200 text-xs font-bold tracking-widest uppercase text-gray-700">
                <span class="w-2 h-2 rounded-full bg-[var(--gold)] animate-pulse"></span>
                Smarter Way To Prepare
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold leading-[1.15] text-[var(--text-main)]">
                Pass Your Govt Exam <br class="hidden md:block" />
                With <span class="gradient-text">Interactive Webbooks</span>
            </h1>
            <p class="text-sm md:text-base text-gray-600 max-w-lg mx-auto md:mx-0 font-medium leading-relaxed">
                Ditch the boring PDFs and long videos. Read smart chapter notes, take instant tests, and let our AI track your weak topics automatically. 
            </p>
            <div class="flex flex-col sm:flex-row items-center gap-3 justify-center md:justify-start pt-2">
                <a href="#exams" class="w-full sm:w-auto px-7 py-3.5 rounded-xl text-sm font-extrabold shadow-lg bg-gray-900 hover:bg-gray-800 transition-all flex items-center justify-center gap-2" style="color: #ffffff !important;">
                    Explore Exams <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="{{ route('free-content') }}" class="w-full sm:w-auto px-7 py-3.5 rounded-xl text-sm font-extrabold text-gray-800 bg-white border border-gray-300 shadow-sm hover:bg-gray-50 transition-all flex items-center justify-center">
                    Try Free Demo
                </a>
            </div>
            <!-- Trust Badges -->
            <div class="pt-3 flex items-center justify-center md:justify-start gap-3">
                <div class="flex -space-x-2">
                    <img class="w-7 h-7 rounded-full border-2 border-white shadow-sm" src="https://i.pravatar.cc/100?img=1" alt="User">
                    <img class="w-7 h-7 rounded-full border-2 border-white shadow-sm" src="https://i.pravatar.cc/100?img=2" alt="User">
                    <img class="w-7 h-7 rounded-full border-2 border-white shadow-sm" src="https://i.pravatar.cc/100?img=3" alt="User">
                </div>
                <p class="text-xs text-gray-600 font-bold">Trusted by 100+ students</p>
            </div>
        </div>

        <!-- Right Content (Bigger Animated Dashboard Card with Non-Overlapping Badges) -->
        <div class="flex-1 w-full relative flex justify-center md:justify-end items-center my-6 md:my-0">
            <!-- Glowing Background Accent -->
            <div class="absolute inset-0 bg-gradient-to-tr from-[var(--gold)]/20 to-blue-500/20 blur-3xl rounded-full transform scale-110"></div>
            
            <!-- Outer Relative Box to Position Floating Badges Cleanly -->
            <div class="relative w-full max-w-lg my-6 px-4">
                
                <!-- Main Composite Card Container -->
                <div class="relative bg-white rounded-2xl border border-gray-200/90 shadow-2xl p-6 md:p-7 space-y-5 animate-float z-10">
                    
                    <!-- Header bar -->
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-gray-900 to-gray-800 text-white flex items-center justify-center font-bold text-base shadow">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <div>
                                <div class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Live Mock Result</div>
                                <div class="text-base font-extrabold text-gray-900">SSC CGL Tier 1</div>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-extrabold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center gap-1.5 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> 85% Score
                        </span>
                    </div>

                    <!-- Simulated Subject Progress Bars -->
                    <div class="space-y-4 pt-1">
                        <div>
                            <div class="flex justify-between text-xs md:text-sm font-bold mb-1.5 text-gray-800">
                                <span>Quantitative Aptitude</span>
                                <span class="text-blue-600 font-extrabold">92%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-blue-600 h-2.5 rounded-full animate-progress" style="width: 92%;"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-xs md:text-sm font-bold mb-1.5 text-gray-800">
                                <span>General Intelligence</span>
                                <span class="text-[var(--gold-deep)] font-extrabold">88%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-[var(--gold)] h-2.5 rounded-full animate-progress" style="width: 88%; animation-delay: 0.3s;"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-xs md:text-sm font-bold mb-1.5 text-gray-800">
                                <span>General Awareness</span>
                                <span class="text-emerald-600 font-extrabold">78%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-emerald-500 h-2.5 rounded-full animate-progress" style="width: 78%; animation-delay: 0.6s;"></div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Floating Badge 1 (Top Left Outer Corner) -->
                <div class="absolute -top-7 left-0 md:-left-6 bg-white border border-gray-200 shadow-xl rounded-xl px-4 py-2.5 flex items-center gap-3 animate-float-delay z-20">
                    <div class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <div class="text-[9px] font-extrabold text-gray-400 uppercase tracking-wider">Syllabus</div>
                        <div class="text-xs font-extrabold text-gray-900">100% Covered</div>
                    </div>
                </div>

                <!-- Floating Badge 2 (Bottom Right Outer Corner) -->
                <div class="absolute -bottom-7 right-0 md:-right-6 bg-white border border-gray-200 shadow-xl rounded-xl px-4 py-2.5 flex items-center gap-3 animate-float z-20" style="animation-delay: 1.2s;">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <div>
                        <div class="text-[9px] font-extrabold text-gray-400 uppercase tracking-wider">Speed Test</div>
                        <div class="text-xs font-extrabold text-gray-900">+15% Faster</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Stats / Partners Banner -->
<section class="py-6 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-6 opacity-90">
            <div class="flex items-center gap-3 text-gray-800 font-extrabold text-sm md:text-base"><i class="fa-solid fa-graduation-cap text-lg text-[var(--gold)]"></i> {{ $totalExams ?? 3 }}+ Active Exams</div>
            <div class="flex items-center gap-3 text-gray-800 font-extrabold text-sm md:text-base"><i class="fa-solid fa-check-double text-lg text-green-500"></i> 100% Syllabus Coverage</div>
            <div class="flex items-center gap-3 text-gray-800 font-extrabold text-sm md:text-base"><i class="fa-solid fa-laptop-code text-lg text-blue-500"></i> AI Error Analytics</div>
            <div class="flex items-center gap-3 text-gray-800 font-extrabold text-sm md:text-base"><i class="fa-solid fa-mobile-screen text-lg text-purple-500"></i> 100% Mobile Responsive</div>
        </div>
    </div>
</section>

<!-- Exams Available Section (INTERACTIVE ALPINE FILTERING) -->
<section id="exams" class="py-14 bg-gray-50" x-data="{ selectedTag: 'all' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-widest text-[var(--theme-active)]">Choose Your Exam</span>
                <h2 class="text-2xl md:text-3xl font-extrabold text-[var(--text-main)] mt-1">Featured Exam Webbooks</h2>
            </div>
            <a href="{{ route('courses') }}" class="text-xs md:text-sm font-bold text-[var(--theme-active)] hover:underline flex items-center gap-1">View All Exams &rarr;</a>
        </div>

        <!-- Interactive Filter Tags / Links -->
        <div class="flex gap-2.5 overflow-x-auto pb-2 scrollbar-hide">
            <a href="{{ route('courses') }}" class="px-4 py-2 rounded-full border text-xs font-bold whitespace-nowrap shadow-sm transition-all cursor-pointer bg-gray-900 text-white border-gray-900 shadow-md">
                All Exams ({{ $courses->count() }})
            </a>
            @foreach($courses as $course)
                <a href="{{ route('exam.details', $course->slug) }}" class="px-4 py-2 rounded-full border text-xs font-bold whitespace-nowrap shadow-sm transition-all cursor-pointer bg-white text-gray-800 border-gray-200 hover:border-gray-400">
                    {{ $course->title_hi }}
                </a>
            @endforeach
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col group">
                    <div class="p-6 space-y-4 flex-1">
                        <div class="flex justify-between items-start">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl text-white bg-gradient-to-br from-gray-900 to-gray-800 group-hover:scale-110 transition-transform shadow-md">
                                <i class="fa-solid fa-book-open"></i>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase bg-red-50 text-red-600 border border-red-100">
                                {{ round((($course->price - $course->discounted_price) / $course->price) * 100) }}% OFF
                            </span>
                        </div>
                        <div>
                            <h3 class="text-xl font-extrabold text-[var(--text-main)] leading-tight">{{ $course->title_hi }}</h3>
                            <p class="text-xs md:text-sm mt-2 text-gray-500 leading-relaxed line-clamp-2">{{ $course->description_hi }}</p>
                        </div>
                        <div class="pt-4 mt-auto border-t border-gray-100">
                            <div class="flex items-end gap-2.5">
                                <span class="text-2xl font-black text-[var(--text-main)] leading-none">₹{{ $course->discounted_price }}</span>
                                <span class="text-sm font-medium line-through text-gray-400 mb-0.5">₹{{ $course->price }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-3.5 bg-gray-50 border-t border-gray-100">
                        <a href="{{ route('exam.details', $course->slug) }}" class="block w-full text-center text-sm font-bold text-gray-900 hover:text-[var(--gold-deep)] transition-colors">
                            View Exam Details &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- SECTION: Webbooks vs Videos Comparison (SPACIOUS) -->
<section class="py-14 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="text-center max-w-2xl mx-auto space-y-2">
            <span class="text-xs font-extrabold uppercase tracking-widest text-[var(--theme-active)]">Compare Methodology</span>
            <h2 class="text-2xl md:text-3xl font-extrabold text-[var(--text-main)]">Why Choose Webbooks Over Videos?</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
            <!-- Videos (Negative) -->
            <div class="p-6 rounded-2xl bg-red-50/60 border border-red-100 space-y-4 shadow-sm">
                <h3 class="font-extrabold text-base text-red-900 flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-xmark text-xl text-red-500"></i> Traditional Video Courses
                </h3>
                <ul class="text-xs md:text-sm text-red-800 space-y-3 font-medium">
                    <li class="flex items-start gap-2.5"><i class="fa-solid fa-minus mt-1 text-red-400"></i> Takes 100+ hours to finish one single subject.</li>
                    <li class="flex items-start gap-2.5"><i class="fa-solid fa-minus mt-1 text-red-400"></i> Passive watching leads to poor long-term memory retention.</li>
                    <li class="flex items-start gap-2.5"><i class="fa-solid fa-minus mt-1 text-red-400"></i> Extremely difficult to revise 10 days before exams.</li>
                </ul>
            </div>
            <!-- Webbooks (Positive) -->
            <div class="p-6 rounded-2xl bg-emerald-50/60 border border-emerald-100 space-y-4 shadow-sm">
                <h3 class="font-extrabold text-base text-emerald-900 flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-xl text-emerald-500"></i> Testwise Webbooks
                </h3>
                <ul class="text-xs md:text-sm text-emerald-800 space-y-3 font-medium">
                    <li class="flex items-start gap-2.5"><i class="fa-solid fa-check mt-1 text-emerald-500"></i> Read concise notes 5x faster than watching videos.</li>
                    <li class="flex items-start gap-2.5"><i class="fa-solid fa-check mt-1 text-emerald-500"></i> Active learning with immediate Chapter MCQ Tests.</li>
                    <li class="flex items-start gap-2.5"><i class="fa-solid fa-check mt-1 text-emerald-500"></i> Auto-generated AI error book for quick final revision.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- How It Works (SPACIOUS) -->
<section class="py-14 bg-gray-50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="text-center max-w-2xl mx-auto space-y-2">
            <span class="text-xs font-extrabold uppercase tracking-widest text-[var(--theme-active)]">Step-by-Step</span>
            <h2 class="text-2xl md:text-3xl font-extrabold text-[var(--text-main)]">4 Steps to Score High</h2>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-gray-200 shadow-sm text-center space-y-2">
                <div class="w-12 h-12 mx-auto rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-base font-black">1</div>
                <h4 class="font-extrabold text-sm md:text-base text-gray-900">Study Notes</h4>
                <p class="text-xs text-gray-500 leading-relaxed">Read point-wise, highly targeted syllabus chapter notes.</p>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-gray-200 shadow-sm text-center space-y-2">
                <div class="w-12 h-12 mx-auto rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-base font-black">2</div>
                <h4 class="font-extrabold text-sm md:text-base text-gray-900">Chapter Test</h4>
                <p class="text-xs text-gray-500 leading-relaxed">Attempt immediate MCQs right after finishing a chapter.</p>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-gray-200 shadow-sm text-center space-y-2">
                <div class="w-12 h-12 mx-auto rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-base font-black">3</div>
                <h4 class="font-extrabold text-sm md:text-base text-gray-900">Error Tracking</h4>
                <p class="text-xs text-gray-500 leading-relaxed">Our AI automatically compiles wrong answers for revision.</p>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-gray-200 shadow-sm text-center space-y-2">
                <div class="w-12 h-12 mx-auto rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-base font-black">4</div>
                <h4 class="font-extrabold text-sm md:text-base text-gray-900">Mock Exams</h4>
                <p class="text-xs text-gray-500 leading-relaxed">Give full-length CBT tests to build speed and confidence.</p>
            </div>
        </div>
    </div>
</section>

<!-- Wall of Fame (Top Performers - SPACIOUS) -->
<section class="py-14 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="text-center max-w-2xl mx-auto space-y-2">
            <span class="text-xs font-extrabold uppercase tracking-widest text-[var(--theme-active)]">Success Stories</span>
            <h2 class="text-2xl md:text-3xl font-extrabold text-[var(--text-main)]">Wall of Fame</h2>
        </div>
        
        <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide snap-x justify-start md:justify-center">
            <div class="snap-center min-w-[240px] bg-gray-50 rounded-2xl p-5 border border-gray-200 text-center flex-shrink-0 relative overflow-hidden shadow-sm">
                <div class="absolute top-0 right-0 w-9 h-9 bg-amber-400 rounded-bl-2xl shadow"></div>
                <i class="fa-solid fa-trophy absolute top-1.5 right-2 text-xs text-white"></i>
                <img class="w-14 h-14 mx-auto rounded-full border-2 border-white shadow-md mb-3" src="https://i.pravatar.cc/100?img=11" alt="Rank 1">
                <div class="text-sm font-extrabold text-gray-900">Rahul Sharma</div>
                <div class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">Rank 1 - SSC CGL</div>
                <div class="inline-block px-3 py-1 bg-white border border-gray-200 rounded-full text-xs font-extrabold text-green-600 shadow-sm">Score: 92%</div>
            </div>

            <div class="snap-center min-w-[240px] bg-gray-50 rounded-2xl p-5 border border-gray-200 text-center flex-shrink-0 relative overflow-hidden shadow-sm">
                <div class="absolute top-0 right-0 w-9 h-9 bg-slate-400 rounded-bl-2xl shadow"></div>
                <img class="w-14 h-14 mx-auto rounded-full border-2 border-white shadow-md mb-3" src="https://i.pravatar.cc/100?img=47" alt="Rank 2">
                <div class="text-sm font-extrabold text-gray-900">Priya Mishra</div>
                <div class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">Rank 2 - MP Police</div>
                <div class="inline-block px-3 py-1 bg-white border border-gray-200 rounded-full text-xs font-extrabold text-green-600 shadow-sm">Score: 89%</div>
            </div>

            <div class="snap-center min-w-[240px] bg-gray-50 rounded-2xl p-5 border border-gray-200 text-center flex-shrink-0 relative overflow-hidden shadow-sm">
                <div class="absolute top-0 right-0 w-9 h-9 bg-orange-400 rounded-bl-2xl shadow"></div>
                <img class="w-14 h-14 mx-auto rounded-full border-2 border-white shadow-md mb-3" src="https://i.pravatar.cc/100?img=12" alt="Rank 3">
                <div class="text-sm font-extrabold text-gray-900">Amit Kumar</div>
                <div class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">Rank 3 - Patwari</div>
                <div class="inline-block px-3 py-1 bg-white border border-gray-200 rounded-full text-xs font-extrabold text-green-600 shadow-sm">Score: 88%</div>
            </div>
        </div>
    </div>
</section>

<!-- Frequently Asked Questions (SPACIOUS) -->
<section class="py-14 bg-gray-50 border-t border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="text-center space-y-2">
            <span class="text-xs font-extrabold uppercase tracking-widest text-[var(--theme-active)]">Have Questions?</span>
            <h2 class="text-2xl md:text-3xl font-extrabold text-[var(--text-main)]">Frequently Asked Questions</h2>
        </div>
        
        <div class="space-y-3">
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm space-y-1.5">
                <h4 class="text-sm md:text-base font-extrabold text-gray-900">Q. Are the mock tests strictly based on the latest exam syllabus?</h4>
                <p class="text-xs md:text-sm text-gray-600 leading-relaxed">Yes, all our mock tests and webbook notes are updated regularly to reflect the exact latest syllabus and TCS exam patterns.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm space-y-1.5">
                <h4 class="text-sm md:text-base font-extrabold text-gray-900">Q. Can I attempt mock tests on my mobile phone?</h4>
                <p class="text-xs md:text-sm text-gray-600 leading-relaxed">Absolutely! Our entire platform is 100% mobile-responsive, so you can read chapter notes and give full CBT mock tests smoothly on any mobile device.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm space-y-1.5">
                <h4 class="text-sm md:text-base font-extrabold text-gray-900">Q. Do you offer a free trial or demo content?</h4>
                <p class="text-xs md:text-sm text-gray-600 leading-relaxed">Yes! You can explore free chapter notes and take a free full mock test on our "Free Content" page without paying anything.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call To Action (SPACIOUS) -->
<section class="py-14 md:py-16 bg-gradient-to-r from-gray-900 to-[#17233F] text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
        <div class="space-y-2">
            <h2 class="text-2xl md:text-3xl font-extrabold text-white" style="color: #ffffff !important;">Ready to boost your exam score?</h2>
            <p class="text-xs md:text-sm text-gray-200" style="color: #e2e8f0 !important;">Join thousands of students preparing smarter with Testwise Webbooks.</p>
        </div>
        <div class="flex gap-3 shrink-0">
            <a href="{{ route('register') }}" class="px-7 py-3.5 rounded-xl text-sm font-extrabold bg-[var(--gold)] text-gray-900 shadow-lg hover:bg-yellow-500 transition-colors">
                Sign Up Now
            </a>
            <a href="{{ route('free-content') }}" class="px-7 py-3.5 rounded-xl text-sm font-extrabold bg-white/10 hover:bg-white/20 border border-white/20 transition-colors">
                Free Demo
            </a>
        </div>
    </div>
</section>
@endsection

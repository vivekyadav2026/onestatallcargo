@extends('layouts.student')

@section('title', 'Student Dashboard - Testwise MP Police GD 2026')

@section('content')
<div class="space-y-8">
    
    <!-- Welcome Greeting Banner -->
    <div class="p-6 sm:p-8 rounded-3xl panel flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden" style="border-color: var(--border-color);">
        <div class="space-y-2 relative z-10">
            <div class="flex items-center gap-3">
                <h1 class="text-2xl sm:text-3xl font-black" style="color: var(--text-main) !important;">नमस्ते, {{ $user->name }}! 👏</h1>
                @if(!$isPro)
                    <span class="px-3 py-1 rounded-full text-xs font-bold" style="background-color: rgba(217,154,43,0.1); color: var(--gold-deep); border: 1px solid var(--gold);">
                        FREE PREVIEW USER
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1" style="background-color: var(--teal-soft); color: var(--teal); border: 1px solid var(--teal);">
                        <i class="fa-solid fa-crown" style="color: var(--gold);"></i> PRO UNLOCKED
                    </span>
                @endif
            </div>
            <p class="text-xs" style="color: var(--text-muted);">{{ $course->title_hi ?? 'MP Police Constable GD 2026' }} भर्ती की आपकी दैनिक तैयारी प्रगति</p>
        </div>

        @if(!$isPro)
            <button type="button" onclick="payWithRazorpay()" class="btn btn-gold text-xs shadow-xl justify-center gap-2 shrink-0 relative z-10 cursor-pointer">
                <i class="fa-solid fa-bolt"></i> कोर्स अनलॉक करें (₹499)
            </button>
        @endif
    </div>

    <!-- Course Progress Bar & Top Stat Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Column: Course Progress & Stats -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Main Progress Card -->
            <div class="p-6 rounded-3xl panel space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-bold text-lg" style="background-color: var(--bg-main); color: var(--theme-bg);">
                            <i class="fa-solid fa-shield"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-base" style="color: var(--text-main) !important;">MP Police Constable GD 2026</h3>
                            <p class="text-xs" style="color: var(--text-muted);">सम्पूर्ण तैयारी पाठ्यक्रम (GK, तर्कशक्ति, विज्ञान व गणित)</p>
                        </div>
                    </div>
                    <span class="text-2xl font-black" style="color: var(--theme-bg);">
                        {{ round(($completedChaptersCount / max(1, $totalChapters)) * 100) }}% पूर्ण
                    </span>
                </div>

                <!-- Progress Bar -->
                <div class="space-y-1.5">
                    <div class="w-full h-3 rounded-full overflow-hidden p-0.5" style="background-color: var(--bg-main); border: 1px solid var(--border-color);">
                        <div class="h-full rounded-full transition-all duration-500" style="background-color: var(--theme-active); width: {{ round(($completedChaptersCount / max(1, $totalChapters)) * 100) }}%;"></div>
                    </div>
                    <div class="flex justify-between text-[11px]" style="color: var(--text-muted);">
                        <span>{{ $completedChaptersCount }} of {{ $totalChapters }} अध्याय पूर्ण</span>
                        <a href="{{ route('student.chapter-tests') }}" class="font-bold hover:underline" style="color: var(--gold-deep);">पूरा सिलेबस देखें →</a>
                    </div>
                </div>
            </div>

            <!-- 4 Metric KPI Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl panel space-y-1">
                    <div class="flex items-center justify-between" style="color: var(--teal);">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded" style="background-color: var(--teal-soft);">CHAPTERS</span>
                    </div>
                    <div class="text-2xl font-black mt-1" style="color: var(--text-main) !important;">{{ $completedChaptersCount }}/{{ $totalChapters }}</div>
                    <div class="text-[11px]" style="color: var(--text-muted);">अध्याय पूर्ण</div>
                </div>

                <div class="p-4 rounded-2xl panel space-y-1">
                    <div class="flex items-center justify-between" style="color: var(--theme-bg);">
                        <i class="fa-solid fa-file-lines text-lg"></i>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded" style="background-color: var(--bg-main);">TESTS</span>
                    </div>
                    <div class="text-2xl font-black mt-1" style="color: var(--text-main) !important;">{{ $recentAttempts->count() }}</div>
                    <div class="text-[11px]" style="color: var(--text-muted);">प्रैक्टिस टेस्ट</div>
                </div>

                <div class="p-4 rounded-2xl panel space-y-1">
                    <div class="flex items-center justify-between" style="color: var(--gold-deep);">
                        <i class="fa-solid fa-crosshair text-lg"></i>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded" style="background-color: rgba(217, 154, 43, 0.1);">ACCURACY</span>
                    </div>
                    <div class="text-2xl font-black mt-1" style="color: var(--text-main) !important;">{{ round($overallAccuracy, 1) }}%</div>
                    <div class="text-[11px]" style="color: var(--text-muted);">सटीकता (Accuracy)</div>
                </div>

                <div class="p-4 rounded-2xl panel space-y-1">
                    <div class="flex items-center justify-between" style="color: var(--teal);">
                        <i class="fa-solid fa-clock text-lg"></i>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded" style="background-color: var(--teal-soft);">TIME</span>
                    </div>
                    <div class="text-2xl font-black mt-1" style="color: var(--text-main) !important;">2h 20m</div>
                    <div class="text-[11px]" style="color: var(--text-muted);">कुल अध्ययन समय</div>
                </div>
            </div>

            <!-- Subject-wise Progress -->
            <div class="p-6 rounded-3xl panel space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-base" style="color: var(--text-main) !important;">विषयवार प्रगति (Subject-wise Coverage)</h3>
                    <a href="{{ route('student.chapter-tests') }}" class="text-xs font-bold hover:underline" style="color: var(--gold-deep);">विस्तृत देखें →</a>
                </div>

                <div class="space-y-4">
                    @foreach($subjects as $sub)
                        <div class="p-4 rounded-2xl space-y-2 border" style="background-color: var(--bg-main); border-color: var(--border-hard);">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold" style="color: var(--text-main) !important;">{{ $sub->name_hi }} ({{ $sub->total_marks }} अंक)</span>
                                <span class="font-semibold" style="color: var(--text-muted);">{{ $sub->completed_chapters }} / {{ $sub->total_count }} अध्याय ({{ $sub->percentage }}%)</span>
                            </div>
                            <div class="w-full h-2 rounded-full overflow-hidden" style="background-color: white;">
                                <div class="h-full rounded-full" style="background-color: var(--theme-bg); width: {{ $sub->percentage }}%;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Test Attempts -->
            <div class="p-6 rounded-3xl panel space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-base" style="color: var(--text-main) !important;">हालिया टेस्ट परिणाम (Recent Attempts)</h3>
                    <a href="{{ route('student.performance') }}" class="text-xs font-bold hover:underline" style="color: var(--gold-deep);">सभी प्रयास देखें →</a>
                </div>

                @if($recentAttempts->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentAttempts as $att)
                            <div class="p-4 rounded-2xl flex items-center justify-between gap-4 border" style="background-color: var(--bg-main); border-color: var(--border-hard);">
                                <div>
                                    <h4 class="text-sm font-bold" style="color: var(--text-main) !important;">
                                        {{ $att->chapter ? $att->chapter->title_hi : ($att->mockTest ? $att->mockTest->title_hi : 'CBT Practice Test') }}
                                    </h4>
                                    <p class="text-[11px]" style="color: var(--text-muted);">
                                        {{ \Carbon\Carbon::parse($att->completed_at)->diffForHumans() }} | Time: {{ floor($att->time_taken_seconds / 60) }}m {{ $att->time_taken_seconds % 60 }}s
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-sm font-black block" style="color: var(--teal);">{{ $att->score }} / {{ $att->total_marks }}</span>
                                    <a href="{{ route('student.test-result', $att->id) }}" class="text-[11px] font-bold hover:underline" style="color: var(--gold-deep);">Review Solution</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center rounded-2xl border space-y-3" style="background-color: var(--bg-main); border-color: var(--border-hard);">
                        <i class="fa-solid fa-file-circle-plus text-3xl" style="color: var(--text-muted);"></i>
                        <p class="text-xs" style="color: var(--text-muted);">आपने अभी तक कोई टेस्ट नहीं दिया है।</p>
                        <a href="{{ route('student.cbt-test', ['type' => 'chapter', 'id' => 1]) }}" class="btn btn-gold text-xs">
                            पहला टेस्ट शुरू करें →
                        </a>
                    </div>
                @endif
            </div>

        </div>

        <!-- Right Column Widgets -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Daily Study Goal & Streak Timer Widget -->
            <div class="p-6 rounded-3xl panel space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider" style="color: var(--text-muted);">YOUR GOAL</span>
                    <span class="text-xs font-extrabold" style="color: var(--theme-active);">MP Police GD 2026</span>
                </div>

                <div class="p-4 rounded-2xl border space-y-2 text-center" style="background-color: var(--bg-main); border-color: var(--border-hard);">
                    <span class="text-xs block" style="color: var(--text-muted);">आज का अध्ययन सत्र (Timer) - दैनिक लक्ष्य: 2 घंटे</span>
                    <div class="text-3xl font-black font-mono tracking-wider" style="color: var(--text-main) !important;">00 min 00s</div>
                    <button class="w-full btn btn-secondary text-xs">
                        <i class="fa-solid fa-play mr-1"></i> टाइमर शुरू करें (Start Session)
                    </button>
                </div>
            </div>

            <!-- 10 Full Mock Tests Card -->
            <div class="p-6 rounded-3xl panel space-y-4">
                <div class="flex items-center justify-between">
                    <h4 class="font-bold text-sm" style="color: var(--text-main) !important;">कुल मॉक टेस्ट (10 MOCKS)</h4>
                    <span class="text-xs font-bold" style="color: var(--theme-bg);">0/10 पूर्ण</span>
                </div>
                <p class="text-xs" style="color: var(--text-muted);">100 प्रश्न • 120 मिनट • रियल CBT पैटर्न</p>

                <div class="p-3.5 rounded-2xl flex justify-between text-xs font-bold border" style="background-color: var(--bg-main); border-color: var(--border-hard);">
                    <span style="color: var(--text-muted);">सर्वश्रेष्ठ अंक: <strong style="color: var(--text-main) !important;">0/100</strong></span>
                    <span style="color: var(--text-muted);">औसत स्कोर: <strong style="color: var(--text-main) !important;">0/100</strong></span>
                </div>

                <a href="{{ route('student.mock-tests') }}" class="w-full btn border text-xs" style="background-color: white; border-color: var(--border-hard); color: var(--text-main);">
                    मॉक टेस्ट शृंखला खोलें →
                </a>
            </div>

            <!-- Weak Topics Inspector Widget -->
            <div class="p-6 rounded-3xl panel space-y-4">
                <div class="flex items-center justify-between">
                    <h4 class="font-bold text-sm" style="color: var(--text-main) !important;">कमजोर विषय संसूचक</h4>
                    <a href="{{ route('student.weak-topics') }}" class="text-xs font-bold hover:underline" style="color: var(--gold-deep);">सभी देखें</a>
                </div>

                <div class="p-4 rounded-2xl border text-center space-y-2" style="background-color: var(--bg-main); border-color: var(--border-hard);">
                    <i class="fa-solid fa-circle-check text-2xl" style="color: var(--teal);"></i>
                    <p class="text-xs font-medium" style="color: var(--text-muted);">अभी कोई गंभीर कमजोर विषय दर्ज नहीं है। नियमित टेस्ट दें।</p>
                    <a href="{{ route('student.weak-topics') }}" class="btn text-xs" style="background-color: white; border-color: var(--border-hard); color: var(--text-main);">
                        कमजोर विषय अभ्यास करें
                    </a>
                </div>
            </div>

            <!-- Error Workbook Widget -->
            <div class="p-6 rounded-3xl panel space-y-4" style="border-color: var(--rose-soft);">
                <div class="flex items-center justify-between">
                    <h4 class="font-bold text-sm" style="color: var(--text-main) !important;">गलती सुधार पुस्तिका</h4>
                    <span class="px-2 py-0.5 rounded font-bold text-[10px]" style="background-color: var(--rose-soft); color: var(--rose);">0 गलतियां</span>
                </div>
                <p class="text-xs" style="color: var(--text-muted);">गलत किए गए प्रश्नों को दोबारा हल करें और अपनी भूलों को शून्य करें।</p>
                <a href="{{ route('student.mistakes') }}" class="w-full btn text-xs shadow-md" style="background-color: var(--rose); color: white; border: none;">
                    गलतियां सुधारें (Review Mistakes)
                </a>
            </div>

            <!-- Official Certificate Card -->
            @if($certificate)
                <div class="p-6 rounded-3xl space-y-3 panel" style="border: 1px solid var(--gold);">
                    <div class="flex items-center gap-2 text-xs font-bold" style="color: var(--gold-deep);">
                        <i class="fa-solid fa-certificate"></i> OFFICIAL CERTIFICATE ISSUED
                    </div>
                    <h4 class="text-sm font-bold" style="color: var(--text-main) !important;">MP Police GD 2026 पाठ्यक्रम पूर्णता प्रमाणपत्र</h4>
                    <p class="text-[11px]" style="color: var(--text-muted);">ID: {{ $certificate->certificate_code }}</p>
                    <a href="{{ route('student.certificate') }}" class="w-full btn btn-gold text-xs shadow-md">
                        प्रमाणपत्र देखें व डाउनलोड करें →
                    </a>
                </div>
            @endif

        </div>

    </div>
</div>
@endsection

@extends('layouts.student')

@section('title', 'Test Result & Analysis - Testwise MP Police GD 2026')

@section('content')
<div class="space-y-8 max-w-4xl mx-auto">
    
    <!-- Score Header Card -->
    <div class="p-8 rounded-3xl panel space-y-6 text-center relative overflow-hidden">
        <div class="space-y-2">
            <span class="px-3 py-1 rounded-full text-xs font-bold" style="background-color: var(--teal-soft); color: var(--teal); border: 1px solid var(--teal);">
                TEST COMPLETED SUCCESSFULLY
            </span>
            <h1 class="text-3xl font-black" style="color: var(--text-main) !important;">Score Analysis & Performance Report</h1>
            <p class="text-xs" style="color: var(--text-muted);">
                {{ $attempt->chapter ? $attempt->chapter->title_hi : ($attempt->mockTest ? $attempt->mockTest->title_hi : 'CBT Practice Test') }}
            </p>
        </div>

        <!-- 4 Score Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 border-t" style="border-color: var(--border-color);">
            <div class="p-4 rounded-2xl border" style="background-color: var(--bg-main); border-color: var(--border-hard);">
                <span class="text-xs block" style="color: var(--text-muted);">आपका स्कोर (Score)</span>
                <span class="text-3xl font-black" style="color: var(--theme-bg);">{{ $attempt->score }} / {{ $attempt->total_marks }}</span>
            </div>
            <div class="p-4 rounded-2xl border" style="background-color: var(--bg-main); border-color: var(--border-hard);">
                <span class="text-xs block" style="color: var(--text-muted);">सटीकता (Accuracy)</span>
                <span class="text-3xl font-black" style="color: var(--teal);">{{ round($attempt->accuracy_percentage, 1) }}%</span>
            </div>
            <div class="p-4 rounded-2xl border" style="background-color: var(--bg-main); border-color: var(--border-hard);">
                <span class="text-xs block" style="color: var(--text-muted);">सही / गलत (R/W)</span>
                <span class="text-2xl font-black mt-1" style="color: var(--text-main) !important;">
                    <span style="color: var(--teal);">{{ $attempt->correct_answers }}</span> / <span style="color: var(--rose);">{{ $attempt->wrong_answers }}</span>
                </span>
            </div>
            <div class="p-4 rounded-2xl border" style="background-color: var(--bg-main); border-color: var(--border-hard);">
                <span class="text-xs block" style="color: var(--text-muted);">समय लिया (Time)</span>
                <span class="text-2xl font-black mt-1" style="color: var(--gold-deep);">
                    {{ floor($attempt->time_taken_seconds / 60) }}m {{ $attempt->time_taken_seconds % 60 }}s
                </span>
            </div>
        </div>
    </div>

    <!-- Detailed Question Solutions List -->
    <div class="space-y-6">
        <h3 class="text-xl font-extrabold" style="color: var(--text-main) !important;">Detailed Solutions Key (प्रश्नों के विस्तृत उत्तर व व्याख्या)</h3>

        <div class="space-y-4">
            @foreach($questions as $index => $q)
                @php
                    $ansData = $attempt->answers_json[$q->id] ?? null;
                    $userOpt = $ansData['user_option'] ?? null;
                    $isCorrect = $ansData['is_correct'] ?? false;
                @endphp

                <div class="p-6 rounded-2xl panel space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <span class="text-xs font-bold" style="color: var(--text-muted);">Question {{ $index + 1 }}</span>
                            <h4 class="text-base font-bold leading-relaxed" style="color: var(--text-main) !important;">{!! nl2br(e($q->question_text_hi)) !!}</h4>
                        </div>

                        @if($userOpt)
                            @if($isCorrect)
                                <span class="px-3 py-1 rounded-full text-xs font-bold shrink-0 flex items-center gap-1" style="background-color: var(--teal-soft); color: var(--teal); border: 1px solid var(--teal);">
                                    <i class="fa-solid fa-check"></i> CORRECT (+1)
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold shrink-0 flex items-center gap-1" style="background-color: var(--rose-soft); color: var(--rose); border: 1px solid var(--rose);">
                                    <i class="fa-solid fa-xmark"></i> WRONG
                                </span>
                            @endif
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold shrink-0" style="background-color: var(--bg-main); color: var(--text-muted); border: 1px solid var(--border-hard);">
                                UNATTEMPTED
                            </span>
                        @endif
                    </div>

                    <!-- Choices Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs font-medium pt-2">
                        @foreach(['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d] as $key => $optVal)
                            <div class="p-3 rounded-xl border flex items-center gap-3 
                                {{ $key === $q->correct_option ? 'bg-teal-50 border-teal-200 text-teal-800 font-bold' : ($key === $userOpt ? 'bg-rose-50 border-rose-200 text-rose-800' : 'bg-gray-50 border-gray-200 text-gray-700') }}">
                                <span class="w-6 h-6 rounded-lg bg-white border border-gray-300 flex items-center justify-center text-xs font-bold font-mono shrink-0">
                                    {{ $key }}
                                </span>
                                <span>{!! e($optVal) !!}</span>
                                @if($key === $q->correct_option)
                                    <i class="fa-solid fa-check text-teal-600 ml-auto"></i>
                                @elseif($key === $userOpt)
                                    <i class="fa-solid fa-xmark text-rose-600 ml-auto"></i>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Detailed Explanation -->
                    @if($q->explanation_hi)
                        <div class="p-4 rounded-xl border text-xs space-y-1" style="background-color: var(--bg-main); border-color: var(--border-hard);">
                            <span class="font-bold block" style="color: var(--theme-bg);"><i class="fa-solid fa-lightbulb mr-1" style="color: var(--gold-deep);"></i> विस्तृत व्याख्या (Solution Explanation):</span>
                            <p class="leading-relaxed" style="color: var(--text-muted);">{!! nl2br(e($q->explanation_hi)) !!}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@extends('layouts.student')

@section('title', ($course->title_hi ?? 'Course') . ' - Course Syllabus & Chapter Tests')

@section('content')
<div class="space-y-6">
    
    <!-- Hero Header Banner -->
    <div class="p-6 sm:p-8 rounded-3xl panel space-y-4">
        <div class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-widest" style="color: var(--theme-active);">
            <span class="w-2 h-2 rounded-full animate-pulse" style="background-color: var(--theme-active);"></span>
            {{ strtoupper($course->title_hi ?? 'EXAM') }} COURSE SYLLABUS
        </div>
        <h1 class="text-2xl sm:text-3xl font-black" style="color: var(--text-main) !important;">{{ $course->title_hi ?? 'Course' }} - Notes & Chapter Tests</h1>
        <p class="text-xs sm:text-sm" style="color: var(--text-muted);">{{ $course->description_hi ?? 'Complete webbook notes and interactive practice tests.' }}</p>

        <!-- Progress stats indicator -->
        <div class="pt-2 flex items-center gap-4 text-xs border-t" style="border-color: var(--border-color); margin-top: 10px; padding-top: 10px;">
            <span class="flex items-center gap-1.5 font-bold" style="color: var(--teal);">
                <i class="fa-solid fa-circle-check"></i> {{ count($userAttempts) }} Completed
            </span>
            <span class="flex items-center gap-1.5 font-bold" style="color: var(--text-muted);">
                <i class="fa-solid fa-clock"></i> {{ max(0, $totalCourseChapters - count($userAttempts)) }} Remaining
            </span>
        </div>
    </div>

    <!-- Subject Filter Tabs (DYNAMIC) -->
    <div class="flex flex-wrap items-center gap-2 pb-2" style="border-bottom: 1px solid var(--border-color);">
        <a href="{{ route('student.chapter-tests', ['subject' => 'all']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $selectedSubject === 'all' ? 'btn-gold shadow-md' : 'border' }}" style="{{ $selectedSubject !== 'all' ? 'background-color: var(--bg-card); border-color: var(--border-hard); color: var(--text-main);' : '' }}">
            All Chapters ({{ $totalCourseChapters }})
        </a>
        @foreach($subjects as $sub)
            <a href="{{ route('student.chapter-tests', ['subject' => $sub->code]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $selectedSubject === $sub->code ? 'btn-gold shadow-md' : 'border' }}" style="{{ $selectedSubject !== $sub->code ? 'background-color: var(--bg-card); border-color: var(--border-hard); color: var(--text-main);' : '' }}">
                {{ $sub->name_hi }} ({{ $sub->chapters->count() }})
            </a>
        @endforeach
    </div>

    <!-- Chapters List -->
    <div class="space-y-3">
        @forelse($chapters as $ch)
            @php
                $attempt = $userAttempts[$ch->id] ?? null;
                $isUnlocked = $ch->is_free_preview || $isPro;
            @endphp

            <div class="p-4 sm:p-5 rounded-2xl border flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="flex items-start sm:items-center gap-4">
                    <span class="w-10 h-10 rounded-xl font-extrabold text-sm flex items-center justify-center border shrink-0" style="background-color: var(--bg-main); border-color: var(--border-hard); color: var(--text-main);">
                        {{ sprintf('%02d', $ch->chapter_number) }}
                    </span>

                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-sm font-bold" style="color: var(--text-main) !important;">{{ $ch->title_hi }}</h3>

                            @if($ch->is_free_preview)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold" style="background-color: var(--teal-soft); color: var(--teal); border: 1px solid var(--teal);">
                                    FREE PREVIEW
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold" style="background-color: rgba(217,154,43,0.1); color: var(--gold-deep); border: 1px solid var(--gold);">
                                    <i class="fa-solid fa-lock text-[9px]"></i> PRO
                                </span>
                            @endif

                            @if($attempt)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold" style="background-color: rgba(20,99,86,0.1); color: var(--teal);">
                                    COMPLETED ({{ $attempt->score }}/{{ $attempt->total_marks }})
                                </span>
                            @endif
                        </div>

                        <p class="text-xs" style="color: var(--text-muted);">{{ $ch->title_en }} • {{ $ch->subject->name_hi ?? 'General' }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2 shrink-0">
                    @if($isUnlocked)
                        <a href="{{ route('student.notes', $ch->id) }}" class="btn btn-secondary text-xs">
                            <i class="fa-solid fa-book-open"></i> Read Notes
                        </a>
                        <a href="{{ route('student.cbt-test', ['type' => 'chapter', 'id' => $ch->id]) }}" class="btn btn-gold text-xs shadow-md">
                            <i class="fa-solid fa-play"></i> Take Test
                        </a>
                    @else
                        <button disabled class="btn text-xs" style="background-color: var(--bg-main); color: var(--text-muted); border: 1px solid var(--border-hard); cursor: not-allowed;">
                            <i class="fa-solid fa-lock"></i> Read Notes
                        </button>
                        <form action="{{ route('student.unlock-pro') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-gold text-xs shadow-md">
                                <i class="fa-solid fa-key"></i> Unlock Test
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-8 text-center rounded-2xl border bg-white" style="border-color: var(--border-color);">
                <i class="fa-solid fa-book-open text-3xl text-gray-300 mb-2"></i>
                <p class="text-sm font-bold text-gray-600">No chapters found for this subject.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

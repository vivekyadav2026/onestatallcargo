@extends('layouts.student')

@section('title', 'कमजोर विषय संसूचक - Testwise MP Police GD 2026')

@section('content')
<div class="space-y-8 max-w-4xl mx-auto">
    <div class="p-6 sm:p-8 rounded-3xl panel space-y-3" style="border-color: var(--gold-deep);">
        <div class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-widest" style="color: var(--gold-deep);">
            <i class="fa-solid fa-chart-line"></i> WEAK TOPICS ANALYZER
        </div>
        <h1 class="text-2xl sm:text-3xl font-black" style="color: var(--text-main) !important;">सर्वाधिक कमजोर विषय (Platform Weak Topics)</h1>
        <p class="text-xs sm:text-sm" style="color: var(--text-muted);">AI संचालित डेटा विश्लेषण द्वारा पहचाने गए वो टॉपिक जिनमें सटीकता 70% से कम है।</p>
    </div>

    <div class="space-y-4">
        @foreach($weakTopics as $wt)
            <div class="p-6 rounded-2xl panel flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <span class="text-[11px] font-bold uppercase" style="color: var(--gold-deep);">{{ $wt['subject'] }}</span>
                    <h3 class="text-base font-bold" style="color: var(--text-main) !important;">{{ $wt['topic_hi'] }}</h3>
                    <p class="text-xs" style="color: var(--text-muted);">{{ $wt['total_questions'] }} छात्रों द्वारा हल किया गया</p>
                </div>

                <div class="flex items-center gap-4 shrink-0">
                    <div class="text-right">
                        <span class="text-xs block" style="color: var(--text-muted);">सटीकता (Accuracy)</span>
                        <span class="text-xl font-black" style="color: var(--rose);">{{ $wt['accuracy'] }}%</span>
                    </div>
                    <a href="{{ route('student.chapter-tests') }}" class="btn btn-gold text-xs shadow-md">
                        अभ्यास करें →
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

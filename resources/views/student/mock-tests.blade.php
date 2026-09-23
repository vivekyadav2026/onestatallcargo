@extends('layouts.student')

@section('title', ($course->title_hi ?? 'Exam') . ' - Full CBT Mock Tests Series')

@section('content')
<div class="space-y-8">
    <div class="p-6 sm:p-8 rounded-3xl panel space-y-3">
        <span class="px-3 py-1 rounded-full text-xs font-bold" style="background-color: rgba(217,154,43,0.1); color: var(--theme-active); border: 1px solid var(--gold);">
            REAL CBT EXAMINATION BLUEPRINT
        </span>
        <h1 class="text-2xl sm:text-3xl font-black" style="color: var(--text-main) !important;">{{ $course->title_hi ?? 'Exam' }} - Full CBT Mock Tests</h1>
        <p class="text-xs sm:text-sm" style="color: var(--text-muted);">Timed CBT exam pattern with instant All-India Rank & accuracy reports.</p>
    </div>

    @if($mockTests->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($mockTests as $mock)
                @php
                    $att = $userAttempts[$mock->id] ?? null;
                    $isUnlocked = $mock->is_free || $isPro;
                @endphp

                <div class="p-6 rounded-3xl panel space-y-4 flex flex-col justify-between border" style="background-color: var(--bg-card); border-color: var(--border-color);">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase" style="color: var(--theme-active);">MOCK TEST {{ sprintf('%02d', $mock->test_number) }}</span>
                            @if($mock->is_free)
                                <span class="px-2.5 py-0.5 rounded-full font-extrabold text-[10px]" style="background-color: var(--teal-soft); color: var(--teal); border: 1px solid var(--teal);">FREE MOCK</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full font-extrabold text-[10px]" style="background-color: rgba(217,154,43,0.1); color: var(--gold-deep); border: 1px solid var(--gold);">PRO</span>
                            @endif
                        </div>

                        <h3 class="text-lg font-bold" style="color: var(--text-main) !important;">{{ $mock->title_hi }}</h3>
                        <p class="text-xs" style="color: var(--text-muted);">{{ $mock->description_hi ?? 'Full length practice test.' }}</p>

                        @if($att)
                            <div class="p-3 rounded-xl flex justify-between text-xs font-bold border" style="background-color: var(--bg-main); border-color: var(--border-hard);">
                                <span style="color: var(--text-muted);">Last Score: <strong style="color: var(--teal);">{{ $att->score }}/{{ $att->total_marks }}</strong></span>
                                <span style="color: var(--text-muted);">Accuracy: <strong style="color: var(--theme-active);">{{ round($att->accuracy_percentage, 1) }}%</strong></span>
                            </div>
                        @endif
                    </div>

                    <div class="pt-4 flex items-center justify-between border-t" style="border-color: var(--border-color);">
                        <span class="text-xs" style="color: var(--text-muted);"><i class="fa-solid fa-clock mr-1" style="color: var(--gold-deep);"></i> {{ $mock->duration_minutes }} Mins</span>

                        @if($isUnlocked)
                            <a href="{{ route('student.cbt-test', ['type' => 'full_mock', 'id' => $mock->id]) }}" class="btn btn-gold text-xs shadow-md">
                                <i class="fa-solid fa-play"></i> Start CBT Test
                            </a>
                        @else
                            <form action="{{ route('student.unlock-pro') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-gold text-xs shadow-md">
                                    <i class="fa-solid fa-lock"></i> Unlock Test (₹499)
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-8 text-center rounded-3xl border bg-white" style="border-color: var(--border-color);">
            <i class="fa-solid fa-clock text-3xl text-gray-300 mb-2"></i>
            <p class="text-sm font-bold text-gray-600">No mock tests available for this exam yet.</p>
        </div>
    @endif
</div>
@endsection

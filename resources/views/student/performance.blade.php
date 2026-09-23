@extends('layouts.student')

@section('title', 'Performance Analytics - Testwise MP Police GD 2026')

@section('content')
<div class="space-y-8 max-w-4xl mx-auto">
    <div class="p-6 sm:p-8 rounded-3xl panel space-y-3">
        <span class="px-3 py-1 rounded-full text-xs font-bold" style="background-color: var(--teal-soft); color: var(--teal); border: 1px solid var(--teal);">
            REAL-TIME EXAM ANALYTICS
        </span>
        <h1 class="text-2xl sm:text-3xl font-black" style="color: var(--text-main) !important;">प्रदर्शन रिपोर्ट (Performance Analytics)</h1>
        <p class="text-xs sm:text-sm" style="color: var(--text-muted);">आपकी सटीकता, स्कोर रुझान और टेस्ट इतिहास का विस्तृत विश्लेषण</p>
    </div>

    <div class="p-6 rounded-3xl panel space-y-4">
        <h3 class="font-bold text-base" style="color: var(--text-main) !important;">सभी टेस्ट प्रयासों की सूची (All Test Attempts)</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" style="color: var(--text-main);">
                <thead class="uppercase font-bold" style="background-color: var(--bg-main); color: var(--text-muted); border-bottom: 1px solid var(--border-color);">
                    <tr>
                        <th class="p-3">Test Title</th>
                        <th class="p-3">Score</th>
                        <th class="p-3">Accuracy</th>
                        <th class="p-3">Time Taken</th>
                        <th class="p-3">Date</th>
                        <th class="p-3">Action</th>
                    </tr>
                </thead>
                <tbody class="font-medium">
                    @foreach($attempts as $att)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td class="p-3 font-bold" style="color: var(--text-main) !important;">
                                {{ $att->chapter ? $att->chapter->title_hi : ($att->mockTest ? $att->mockTest->title_hi : 'CBT Practice Test') }}
                            </td>
                            <td class="p-3 font-bold" style="color: var(--teal);">{{ $att->score }} / {{ $att->total_marks }}</td>
                            <td class="p-3 font-bold" style="color: var(--theme-active);">{{ round($att->accuracy_percentage, 1) }}%</td>
                            <td class="p-3" style="color: var(--text-muted);">{{ floor($att->time_taken_seconds / 60) }}m {{ $att->time_taken_seconds % 60 }}s</td>
                            <td class="p-3" style="color: var(--text-muted);">{{ \Carbon\Carbon::parse($att->completed_at)->format('d M Y') }}</td>
                            <td class="p-3">
                                <a href="{{ route('student.test-result', $att->id) }}" class="font-bold hover:underline" style="color: var(--gold-deep);">Solution Key</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

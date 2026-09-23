@extends('layouts.student')

@section('title', 'Study Notes - ' . $chapter->title_hi)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('student.chapter-tests') }}" class="text-xs font-bold hover:underline flex items-center gap-1.5" style="color: var(--text-muted);">
            <i class="fa-solid fa-arrow-left"></i> Back to Chapters
        </a>
        <a href="{{ route('student.cbt-test', ['type' => 'chapter', 'id' => $chapter->id]) }}" class="btn btn-gold text-xs shadow-md flex items-center gap-2">
            <span>Take Chapter Test</span>
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>

    <!-- Notes Content Card -->
    <div class="p-8 rounded-3xl panel space-y-6">
        <div class="pb-4 border-b" style="border-color: var(--border-color);">
            <span class="px-3 py-1 rounded-full text-xs font-bold" style="background-color: var(--bg-main); color: var(--theme-active); border: 1px solid var(--border-color);">
                CHAPTER {{ sprintf('%02d', $chapter->chapter_number) }} • {{ $chapter->subject->name_hi ?? '' }}
            </span>
            <h1 class="text-2xl font-black mt-2" style="color: var(--text-main) !important;">{{ $chapter->title_hi }}</h1>
            <p class="text-xs" style="color: var(--text-muted);">{{ $chapter->title_en }}</p>
        </div>

        @if($chapter->pdf_url)
            <div class="w-full rounded-xl overflow-hidden border border-gray-200" style="height: 70vh;">
                <iframe src="{{ asset($chapter->pdf_url) }}#toolbar=0" width="100%" height="100%" class="border-0"></iframe>
            </div>
        @else
            <div class="prose max-w-none text-sm leading-relaxed space-y-4" style="color: var(--text-main) !important;">
                {!! $chapter->notes_content_hi !!}
            </div>
        @endif

        <div class="pt-6 border-t flex justify-end" style="border-color: var(--border-color);">
            <a href="{{ route('student.cbt-test', ['type' => 'chapter', 'id' => $chapter->id]) }}" class="btn btn-gold text-sm shadow-md flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square"></i> Take Chapter Practice Test Now
            </a>
        </div>
    </div>
</div>
@endsection

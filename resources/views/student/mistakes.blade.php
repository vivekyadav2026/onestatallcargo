@extends('layouts.student')

@section('title', 'गलती सुधार पुस्तिका - Testwise MP Police GD 2026')

@section('content')
<div class="space-y-8 max-w-4xl mx-auto">
    <div class="p-6 sm:p-8 rounded-3xl panel space-y-3" style="border-color: var(--rose-soft);">
        <div class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-widest" style="color: var(--rose);">
            <i class="fa-solid fa-triangle-exclamation"></i> MISTAKE REVIEW WORKBOOK
        </div>
        <h1 class="text-2xl sm:text-3xl font-black" style="color: var(--text-main) !important;">गलती सुधार पुस्तिका (Error Workbook)</h1>
        <p class="text-xs sm:text-sm" style="color: var(--text-muted);">टेस्ट के दौरान आपके द्वारा गलत किए गए प्रश्नों का संग्रह। इन्हें दोहराएं और अपनी परीक्षा सटीकता 100% करें।</p>
    </div>

    @if(count($mistakeQuestions) > 0)
        <div class="space-y-4">
            @foreach($mistakeQuestions as $index => $q)
                <div class="p-6 rounded-2xl panel space-y-4">
                    <div class="flex items-start justify-between">
                        <span class="text-xs font-bold" style="color: var(--rose);">Mistake #{{ $index + 1 }} • {{ $q->subject->name_hi ?? '' }}</span>
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold" style="background-color: var(--rose-soft); color: var(--rose);">Needs Review</span>
                    </div>

                    <h4 class="text-base font-bold leading-relaxed" style="color: var(--text-main) !important;">{!! nl2br(e($q->question_text_hi)) !!}</h4>

                    <div class="p-4 rounded-xl border text-xs space-y-1" style="background-color: var(--bg-main); border-color: var(--border-hard);">
                        <span class="font-bold block" style="color: var(--teal);"><i class="fa-solid fa-circle-check mr-1"></i> सही उत्तर: विकल्प {{ $q->correct_option }}</span>
                        <p class="leading-relaxed" style="color: var(--text-muted);">{!! nl2br(e($q->explanation_hi)) !!}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-12 text-center rounded-3xl panel space-y-3">
            <i class="fa-solid fa-circle-check text-4xl" style="color: var(--teal);"></i>
            <h3 class="text-lg font-bold" style="color: var(--text-main) !important;">बधाई हो! आपकी कोई अनसुलझी गलती नहीं है।</h3>
            <p class="text-xs" style="color: var(--text-muted);">नियमित रूप से अध्याय टेस्ट एवं मॉक टेस्ट देते रहें।</p>
        </div>
    @endif
</div>
@endsection

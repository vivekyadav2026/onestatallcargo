@extends('layouts.app')

@section('title', 'Free Preview Content - Testwise MP Police GD 2026')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10">
    <div class="text-center max-w-3xl mx-auto space-y-3">
        <span class="px-3 py-1 rounded-full text-xs font-bold border" style="background-color: var(--teal-soft); color: var(--teal); border-color: var(--teal);">NO CREDIT CARD REQUIRED</span>
        <h1 class="text-3xl font-extrabold" style="color: var(--text-main) !important;">Free Preview Notes & Chapter Tests</h1>
        <p class="text-sm" style="color: var(--text-muted);">प्रथम 3 अध्यायों के नोट्स और टेस्ट का पूर्ण एक्सेस बिना किसी शुल्क के आजमाएं</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
        @foreach($freeChapters as $ch)
            <div class="p-4 sm:p-6 rounded-3xl panel space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-full font-bold text-xs" style="background-color: rgba(217,154,43,0.1); color: var(--gold-deep);">
                            Chapter {{ $ch->chapter_number }}
                        </span>
                        <span class="px-2 py-0.5 rounded font-bold text-[10px]" style="background-color: var(--teal-soft); color: var(--teal);">FREE</span>
                    </div>

                    <h3 class="text-lg font-bold" style="color: var(--text-main) !important;">{{ $ch->title_hi }}</h3>
                    <p class="text-xs" style="color: var(--text-muted);">{{ $ch->title_en }}</p>
                    <p class="text-xs leading-relaxed" style="color: var(--text-muted);">{{ $ch->description_hi }}</p>
                </div>

                <div class="pt-4 border-t flex items-center justify-between" style="border-color: var(--border-color);">
                    <a href="{{ route('login') }}" class="btn btn-gold text-xs shadow-md">
                        Read Notes
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-secondary text-xs">
                        Take Free Test
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

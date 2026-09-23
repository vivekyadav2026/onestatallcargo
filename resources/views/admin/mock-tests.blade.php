@extends('layouts.admin')

@section('title', 'CBT Mock Tests Manager - Admin Console')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 sm:p-8 rounded-3xl panel space-y-4 relative overflow-hidden">
        <div>
            <h1 class="text-2xl font-black" style="color: var(--text-main) !important;">10 Full CBT Mock Tests Manager</h1>
            <p class="text-xs" style="color: var(--text-muted);">फुल मॉक प्रश्नपत्र, समयसीमा एवं फ्री/प्रो एक्सेस कॉन्फ़िगर करें</p>
        </div>

        <button onclick="document.getElementById('new-mock-modal').classList.remove('hidden')" class="btn btn-gold text-xs shadow-md">
            <i class="fa-solid fa-plus"></i> + नया मॉक टेस्ट बनाएं
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($mockTests as $m)
            <div class="p-6 rounded-3xl panel space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase" style="color: var(--theme-active);">FULL MOCK TEST {{ sprintf('%02d', $m->test_number) }}</span>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] px-2 py-0.5 rounded-full" style="background-color: var(--border-color); color: var(--text-muted);">{{ $m->course ? $m->course->title_hi : 'N/A' }}</span>
                        @if($m->is_free)
                            <span class="px-2.5 py-0.5 rounded-full font-extrabold text-[10px]" style="background-color: var(--teal-soft); color: var(--teal); border: 1px solid var(--teal);">FREE PREVIEW</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full font-extrabold text-[10px]" style="background-color: var(--bg-main); color: var(--text-muted); border: 1px solid var(--border-hard);">PRO</span>
                        @endif
                    </div>
                </div>

                <h3 class="text-lg font-bold mt-2" style="color: var(--text-main) !important;">{{ $m->title_hi }}</h3>
                <p class="text-xs" style="color: var(--text-muted);">{{ $m->duration_minutes }} मिनट • {{ $m->total_questions }} प्रश्न • {{ $m->total_marks }} अंक</p>

                <div class="pt-2 flex items-center justify-between text-xs border-t" style="border-color: var(--border-color); margin-top: 10px; padding-top: 10px;">
                    <span class="font-bold" style="color: var(--text-muted);"><i class="fa-solid fa-question-circle" style="color: var(--theme-active);"></i> {{ $m->questions_count }} प्रश्न जोड़े गए</span>
                    <a href="{{ route('admin.questions') }}" class="font-bold hover:underline" style="color: var(--gold-deep);">Manage Questions →</a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal Form for Adding New Mock Test -->
<div id="new-mock-modal" class="fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="p-8 rounded-3xl panel max-w-lg w-full space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold" style="color: var(--text-main) !important;">+ नया मॉक टेस्ट बनाएं</h3>
            <button onclick="document.getElementById('new-mock-modal').classList.add('hidden')" style="color: var(--text-muted);">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.mock-tests.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div class="field">
                <label>Course / Exam</label>
                <select name="course_id" required>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title_hi }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label>Test Number (क्रम संख्या)</label>
                <input type="number" name="test_number" value="11" required>
            </div>

            <div class="field">
                <label>Title (Hindi)</label>
                <input type="text" name="title_hi" placeholder="e.g. फुल मॉक टेस्ट 11 (सम्पूर्ण पाठ्यक्रम)" required>
            </div>

            <div class="field">
                <label>Title (English)</label>
                <input type="text" name="title_en" placeholder="e.g. Full Mock Test 11" required>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="field">
                    <label>Duration (Minutes)</label>
                    <input type="number" name="duration_minutes" value="120" required>
                </div>
                <div class="field">
                    <label>Total Questions</label>
                    <input type="number" name="total_questions" value="100" required>
                </div>
            </div>

            <button type="submit" class="w-full btn btn-gold font-bold shadow-lg" style="margin-top: 20px;">
                सेव करें (Create Mock Test)
            </button>
        </form>
    </div>
</div>
@endsection

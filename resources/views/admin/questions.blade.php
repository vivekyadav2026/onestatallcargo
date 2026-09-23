@extends('layouts.admin')

@section('title', 'Question Bank Management - Admin Console')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 sm:p-8 rounded-3xl panel space-y-4 relative overflow-hidden">
        <div>
            <h1 class="text-2xl font-black" style="color: var(--text-main) !important;">Question Bank Manager</h1>
            <p class="text-xs" style="color: var(--text-muted);">सभी विषयों और अध्यायों के प्रश्नों का प्रबंधन</p>
        </div>

        <div class="flex items-center gap-3">
            <form action="{{ route('admin.questions') }}" method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search question text..." class="text-xs p-2 rounded border" style="background-color: var(--bg-main); border-color: var(--border-hard);">
                <button type="submit" class="btn btn-gold text-xs px-3 shadow-md">Search</button>
            </form>
            <button onclick="document.getElementById('new-question-modal').classList.remove('hidden')" class="btn btn-gold text-xs shadow-md shrink-0">
                <i class="fa-solid fa-plus"></i> + नया प्रश्न जोड़ें
            </button>
        </div>
    </div>

    <!-- Questions List -->
    <div class="space-y-4">
        @foreach($questions as $q)
            <div class="p-6 rounded-2xl panel space-y-3">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold" style="color: var(--theme-active);">ID: #{{ $q->id }} • {{ $q->subject->name_hi ?? 'General' }}</span>
                    <span class="px-2.5 py-0.5 rounded font-bold" style="background-color: var(--teal-soft); color: var(--teal); border: 1px solid var(--teal);">Correct: Option {{ $q->correct_option }}</span>
                </div>
                <h4 class="text-sm font-bold leading-relaxed" style="color: var(--text-main) !important;">{!! nl2br(e($q->question_text_hi)) !!}</h4>
                <div class="grid grid-cols-2 gap-2 text-xs font-medium" style="color: var(--text-muted);">
                    <div>A: {{ $q->option_a }}</div>
                    <div>B: {{ $q->option_b }}</div>
                    <div>C: {{ $q->option_c }}</div>
                    <div>D: {{ $q->option_d }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="pt-4">
        {{ $questions->links() }}
    </div>
</div>

<!-- Modal Form for Adding New Question -->
<div id="new-question-modal" class="fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="p-8 rounded-3xl panel max-w-xl w-full space-y-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold" style="color: var(--text-main) !important;">+ नया प्रश्न जोड़ें</h3>
            <button onclick="document.getElementById('new-question-modal').classList.add('hidden')" style="color: var(--text-muted);">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.questions.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div class="field">
                <label>
                    Subject
                    <a href="{{ route('admin.subjects') }}" class="font-normal" style="color: var(--teal); font-size: 10px;">+ Add New Subject</a>
                </label>
                <select name="subject_id">
                    @foreach($subjects as $s)
                        <option value="{{ $s->id }}">{{ $s->name_hi }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label>
                    Map to Chapter (Optional)
                    <a href="{{ route('admin.chapters') }}" class="font-normal" style="color: var(--teal); font-size: 10px;">+ Add New Chapter</a>
                </label>
                <select name="chapter_id">
                    <option value="">-- None --</option>
                    @foreach($chapters as $c)
                        <option value="{{ $c->id }}">Ch {{ $c->chapter_number }}: {{ $c->title_hi }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label>
                    Map to Mock Test (Optional)
                    <a href="{{ route('admin.mock-tests') }}" class="font-normal" style="color: var(--teal); font-size: 10px;">+ Add New Mock Test</a>
                </label>
                <select name="mock_test_id">
                    <option value="">-- None --</option>
                    @foreach($mockTests as $m)
                        <option value="{{ $m->id }}">Mock {{ $m->test_number }}: {{ $m->title_hi }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label>Question Text (Hindi)</label>
                <textarea name="question_text_hi" rows="2" required placeholder="प्रश्न हिंदी में दर्ज करें..."></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="field">
                    <label>Option A</label>
                    <input type="text" name="option_a" required>
                </div>
                <div class="field">
                    <label>Option B</label>
                    <input type="text" name="option_b" required>
                </div>
                <div class="field">
                    <label>Option C</label>
                    <input type="text" name="option_c" required>
                </div>
                <div class="field">
                    <label>Option D</label>
                    <input type="text" name="option_d" required>
                </div>
            </div>

            <div class="field">
                <label>Correct Option</label>
                <select name="correct_option">
                    <option value="A">Option A</option>
                    <option value="B">Option B</option>
                    <option value="C">Option C</option>
                    <option value="D">Option D</option>
                </select>
            </div>

            <div class="field">
                <label>Explanation (Hindi Solution)</label>
                <textarea name="explanation_hi" rows="2" placeholder="विस्तृत व्याख्या..."></textarea>
            </div>

            <button type="submit" class="w-full btn btn-gold font-bold shadow-lg" style="margin-top: 20px;">
                प्रश्न सेव करें (Save Question)
            </button>
        </form>
    </div>
</div>
@endsection

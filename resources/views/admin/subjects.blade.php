@extends('layouts.admin')

@section('title', 'Subject Management - Testwise Admin Console')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 sm:p-8 rounded-3xl panel space-y-4 relative overflow-hidden">
        <div>
            <h1 class="text-2xl font-black" style="color: var(--text-main) !important;">Subject Management</h1>
            <p class="text-xs" style="color: var(--text-muted);">पाठ्यक्रम के मुख्य विषयों का प्रबंधन करें</p>
        </div>

        <button onclick="document.getElementById('new-subject-modal').classList.remove('hidden')" class="btn btn-gold text-xs shadow-md">
            <i class="fa-solid fa-plus"></i> + नया विषय जोड़ें
        </button>
    </div>

    <!-- Subject List Table -->
    <div class="p-6 rounded-3xl panel space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" style="color: var(--text-main);">
                <thead class="uppercase font-bold" style="background-color: var(--bg-main); color: var(--text-muted); border-bottom: 1px solid var(--border-color);">
                    <tr>
                        <th class="p-3.5">#ID</th>
                        <th class="p-3.5">Course</th>
                        <th class="p-3.5">विषय (Hindi & English)</th>
                        <th class="p-3.5">विषय कोड</th>
                        <th class="p-3.5">कुल अंक</th>
                        <th class="p-3.5">जुड़े हुए चैप्टर्स</th>
                    </tr>
                </thead>
                <tbody class="font-medium">
                    @foreach($subjects as $sub)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td class="p-3.5 font-bold font-mono" style="color: var(--text-muted);">{{ sprintf('%02d', $sub->id) }}</td>
                            <td class="p-3.5 text-[10px] font-bold" style="color: var(--theme-active);">
                                {{ $sub->course ? $sub->course->title_hi : 'N/A' }}
                            </td>
                            <td class="p-3.5">
                                <span class="font-bold block" style="color: var(--text-main) !important;">{{ $sub->name_hi }}</span>
                                <span class="text-[11px]" style="color: var(--text-muted);">{{ $sub->name_en }}</span>
                            </td>
                            <td class="p-3.5 font-semibold" style="color: var(--text-muted);">{{ $sub->code }}</td>
                            <td class="p-3.5 font-bold" style="color: var(--theme-active);">{{ $sub->total_marks }}</td>
                            <td class="p-3.5">
                                <span class="px-2.5 py-0.5 rounded-full font-bold" style="background-color: var(--bg-main); color: var(--text-muted); border: 1px solid var(--border-hard);">
                                    {{ $sub->chapters->count() }} Chapters
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form for Adding New Subject -->
<div id="new-subject-modal" class="fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="p-8 rounded-3xl panel max-w-lg w-full space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold" style="color: var(--text-main) !important;">+ नया विषय जोड़ें</h3>
            <button onclick="document.getElementById('new-subject-modal').classList.add('hidden')" style="color: var(--text-muted);">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.subjects.store') }}" method="POST" class="space-y-4 text-xs">
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
                <label>Name (Hindi)</label>
                <input type="text" name="name_hi" placeholder="e.g. सामान्य ज्ञान" required>
            </div>

            <div class="field">
                <label>Name (English)</label>
                <input type="text" name="name_en" placeholder="e.g. General Knowledge" required>
            </div>

            <div class="field">
                <label>Subject Code</label>
                <input type="text" name="code" placeholder="e.g. GK" required>
            </div>

            <div class="field">
                <label>Total Marks (अंक)</label>
                <input type="number" name="total_marks" value="30" required>
            </div>

            <button type="submit" class="w-full btn btn-gold font-bold shadow-lg" style="margin-top: 20px;">
                सेव करें (Save Subject)
            </button>
        </form>
    </div>
</div>
@endsection

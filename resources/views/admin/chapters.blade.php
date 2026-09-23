@extends('layouts.admin')

@section('title', 'Chapter Management - Testwise Admin Console')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 sm:p-8 rounded-3xl panel space-y-4 relative overflow-hidden">
        <div>
            <h1 class="text-2xl font-black" style="color: var(--text-main) !important;">Chapter & Free Preview Management</h1>
            <p class="text-xs" style="color: var(--text-muted);">32 अध्यायों का प्रबंधन एवं Free Preview / Pro स्थिति सेट करें</p>
        </div>

        <button onclick="document.getElementById('new-chapter-modal').classList.remove('hidden')" class="btn btn-gold text-xs shadow-md">
            <i class="fa-solid fa-plus"></i> + नया चैप्टर जोड़ें
        </button>
    </div>

    <!-- Chapter List Table -->
    <div class="p-6 rounded-3xl panel space-y-4">
        <!-- Search Form -->
        <div class="px-6 pt-6 pb-2">
            <form method="GET" action="{{ route('admin.chapters') }}" class="flex items-center gap-2 max-w-sm">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search chapters..." class="w-full text-xs px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900">
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-lg text-xs font-bold">Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.chapters') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-xs font-bold">Clear</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" style="color: var(--text-main);">
                <thead class="uppercase font-bold" style="background-color: var(--bg-main); color: var(--text-muted); border-bottom: 1px solid var(--border-color);">
                    <tr>
                        <th class="p-3.5">#</th>
                        <th class="p-3.5">अध्याय का नाम (Hindi & English)</th>
                        <th class="p-3.5">विषय (Subject)</th>
                        <th class="p-3.5">फ्री ट्रायल (Free Preview)</th>
                        <th class="p-3.5">PDF</th>
                        <th class="p-3.5">एक्शन</th>
                    </tr>
                </thead>
                <tbody class="font-medium">
                    @forelse($chapters as $ch)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td class="p-3.5 font-bold font-mono" style="color: var(--text-muted);">{{ sprintf('%02d', $ch->chapter_number) }}</td>
                            <td class="p-3.5">
                                <span class="font-bold block" style="color: var(--text-main) !important;">{{ $ch->title_hi }}</span>
                                <span class="text-[11px]" style="color: var(--text-muted);">{{ $ch->title_en }}</span>
                            </td>
                            <td class="p-3.5 font-semibold" style="color: var(--text-muted);">{{ $ch->subject->name_hi ?? '' }}</td>
                            <td class="p-3.5">
                                @if($ch->is_free_preview)
                                    <span class="px-2.5 py-0.5 rounded-full font-bold" style="background-color: var(--teal-soft); color: var(--teal); border: 1px solid var(--teal);">
                                        FREE PREVIEW ON
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full font-bold" style="background-color: var(--bg-main); color: var(--text-muted); border: 1px solid var(--border-hard);">
                                        LOCKED (PRO)
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5">
                                @if($ch->pdf_url)
                                    <span class="text-xs text-green-600 font-bold"><i class="fa-solid fa-check"></i> Has PDF</span>
                                @else
                                    <span class="text-xs text-gray-400 font-bold">No PDF</span>
                                @endif
                            </td>
                            <td class="p-3.5">
                                <form action="{{ route('admin.chapters.toggle-free', $ch->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 rounded-lg text-[11px] font-bold border" style="background-color: var(--bg-main); border-color: var(--border-hard); color: var(--gold-deep);">
                                        Toggle Free Access
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-500">No chapters found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4">
            {{ $chapters->links() }}
        </div>
    </div>
</div>

<!-- Modal Form for Adding New Chapter -->
<div id="new-chapter-modal" class="fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="p-8 rounded-3xl panel max-w-lg w-full space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold" style="color: var(--text-main) !important;">+ नया चैप्टर जोड़ें</h3>
            <button onclick="document.getElementById('new-chapter-modal').classList.add('hidden')" style="color: var(--text-muted);">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.chapters.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf
            <div class="field">
                <label>Subject</label>
                <select name="subject_id">
                    @foreach($subjects as $s)
                        <option value="{{ $s->id }}">{{ $s->name_hi }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label>Chapter Number</label>
                <input type="number" name="chapter_number" value="33" required>
            </div>

            <div class="field">
                <label>Title (Hindi)</label>
                <input type="text" name="title_hi" placeholder="e.g. मध्य प्रदेश के प्रमुख उद्योग" required>
            </div>

            <div class="field">
                <label>Title (English)</label>
                <input type="text" name="title_en" placeholder="e.g. Major Industries of MP" required>
            </div>
            
            <div class="field">
                <label>PDF Notes (Optional)</label>
                <input type="file" name="pdf_file" accept=".pdf" class="bg-gray-50 border p-2 rounded-xl">
            </div>

            <label class="flex items-center gap-2 cursor-pointer" style="justify-content: flex-start !important;">
                <input type="checkbox" name="is_free_preview">
                <span style="color: var(--text-muted);">Free Preview Toggle</span>
            </label>

            <button type="submit" class="w-full btn btn-gold font-bold shadow-lg" style="margin-top: 20px;">
                सेव करें (Save Chapter)
            </button>
        </form>
    </div>
</div>
@endsection

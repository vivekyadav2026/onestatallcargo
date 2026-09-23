@extends('layouts.admin')

@section('title', 'Certificates Log - Admin Console')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 sm:p-8 rounded-3xl panel space-y-4 relative overflow-hidden">
        <div>
            <h1 class="text-2xl font-black" style="color: var(--text-main) !important;">Issued Certificates & Verification Logs</h1>
            <p class="text-xs" style="color: var(--text-muted);">जारी किए गए प्रमाण-पत्रों का रिकॉर्ड एवं सत्यापन</p>
        </div>

        <div class="flex items-center gap-3">
            <form action="{{ route('admin.certificates') }}" method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search code or email..." class="text-xs p-2 rounded border" style="background-color: var(--bg-main); border-color: var(--border-hard);">
                <button type="submit" class="btn btn-gold text-xs px-3 shadow-md">Search</button>
            </form>
            <button onclick="document.getElementById('new-cert-modal').classList.remove('hidden')" class="btn btn-gold text-xs shadow-md shrink-0">
                <i class="fa-solid fa-award"></i> + नया प्रमाणपत्र जारी करें
            </button>
        </div>
    </div>

    <div class="p-6 rounded-3xl panel space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" style="color: var(--text-main);">
                <thead class="uppercase font-bold" style="background-color: var(--bg-main); color: var(--text-muted); border-bottom: 1px solid var(--border-color);">
                    <tr>
                        <th class="p-3.5">Certificate Code</th>
                        <th class="p-3.5">अभ्यर्थी (Student)</th>
                        <th class="p-3.5">कोर्स (Course Name)</th>
                        <th class="p-3.5">स्कोर (Score)</th>
                        <th class="p-3.5">सत्यापन स्थिति</th>
                        <th class="p-3.5">जारी करने की तिथि</th>
                        <th class="p-3.5">एक्शन</th>
                    </tr>
                </thead>
                <tbody class="font-medium">
                    @foreach($certificates as $c)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td class="p-3.5 font-mono font-bold" style="color: var(--gold-deep);">{{ $c->certificate_code }}</td>
                            <td class="p-3.5 font-bold" style="color: var(--text-main) !important;">{{ $c->user->name ?? 'Student' }}</td>
                            <td class="p-3.5" style="color: var(--text-muted);">{{ $c->course_name }}</td>
                            <td class="p-3.5 font-bold" style="color: var(--teal);">{{ $c->score_achieved }}%</td>
                            <td class="p-3.5">
                                <span class="px-2.5 py-0.5 rounded-full font-bold" style="background-color: var(--teal-soft); color: var(--teal); border: 1px solid var(--teal);">
                                    VERIFIED
                                </span>
                            </td>
                            <td class="p-3.5" style="color: var(--text-muted);">{{ \Carbon\Carbon::parse($c->issue_date)->format('d M Y') }}</td>
                            <td class="p-3.5 flex items-center gap-2">
                                <button onclick="document.getElementById('edit-cert-modal-{{ $c->id }}').classList.remove('hidden')" class="px-3 py-1 rounded-lg text-[11px] font-bold border" style="background-color: var(--bg-main); border-color: var(--border-hard); color: var(--teal);" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('admin.certificates.destroy', $c->id) }}" method="POST" onsubmit="return confirm('क्या आप वाकई इस प्रमाणपत्र को हटाना चाहते हैं?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 rounded-lg text-[11px] font-bold border" style="background-color: var(--rose-soft); border-color: var(--rose); color: var(--rose);" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Certificate Modal -->
                        <div id="edit-cert-modal-{{ $c->id }}" class="fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
                            <div class="p-8 rounded-3xl panel max-w-lg w-full space-y-6">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-bold" style="color: var(--text-main) !important;">प्रमाणपत्र संपादित करें</h3>
                                    <button onclick="document.getElementById('edit-cert-modal-{{ $c->id }}').classList.add('hidden')" style="color: var(--text-muted);">
                                        <i class="fa-solid fa-xmark text-lg"></i>
                                    </button>
                                </div>

                                <form action="{{ route('admin.certificates.update', $c->id) }}" method="POST" class="space-y-4 text-xs">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="field">
                                        <label>Course Name</label>
                                        <input type="text" name="course_name" value="{{ $c->course_name }}" required>
                                    </div>
                                    <div class="field">
                                        <label>Score Achieved (%)</label>
                                        <input type="number" step="0.1" name="score_achieved" value="{{ $c->score_achieved }}" required>
                                    </div>
                                    <div class="field">
                                        <label>Issue Date</label>
                                        <input type="date" name="issue_date" value="{{ \Carbon\Carbon::parse($c->issue_date)->format('Y-m-d') }}" required>
                                    </div>

                                    <button type="submit" class="w-full btn btn-gold font-bold shadow-lg" style="margin-top: 20px;">
                                        अपडेट करें
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $certificates->links() }}
        </div>
    </div>
</div>

<!-- New Certificate Modal -->
<div id="new-cert-modal" class="fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="p-8 rounded-3xl panel max-w-lg w-full space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold" style="color: var(--text-main) !important;">+ नया प्रमाणपत्र जारी करें</h3>
            <button onclick="document.getElementById('new-cert-modal').classList.add('hidden')" style="color: var(--text-muted);">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.certificates.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            
            <div class="field">
                <label>अभ्यर्थी (Student)</label>
                <select name="user_id" required>
                    <option value="">-- अभ्यर्थी चुनें --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}">{{ $st->name }} ({{ $st->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label>Course Name</label>
                <input type="text" name="course_name" value="MP Police Constable GD 2026" required>
            </div>
            <div class="field">
                <label>Score Achieved (%)</label>
                <input type="number" step="0.1" name="score_achieved" placeholder="e.g. 85.5" required>
            </div>
            <div class="field">
                <label>Issue Date</label>
                <input type="date" name="issue_date" value="{{ date('Y-m-d') }}" required>
            </div>
            
            <button type="submit" class="w-full btn btn-gold font-bold shadow-lg" style="margin-top: 20px;">
                प्रमाणपत्र सेव करें
            </button>
        </form>
    </div>
</div>
@endsection

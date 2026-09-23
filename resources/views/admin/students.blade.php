@extends('layouts.admin')

@section('title', 'Students & Access Directory - Admin Console')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 sm:p-8 rounded-3xl panel space-y-4 relative overflow-hidden">
        <div>
            <h1 class="text-2xl font-black" style="color: var(--text-main) !important;">Students Directory & Access Control</h1>
            <p class="text-xs" style="color: var(--text-muted);">पंजीकृत अभ्यर्थी, Pro एक्सेस स्थिति एवं परीक्षा प्रयासों का प्रबंधन</p>
        </div>

        <div class="flex items-center gap-3">
            <form action="{{ route('admin.students') }}" method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..." class="text-xs p-2 rounded border" style="background-color: var(--bg-main); border-color: var(--border-hard);">
                <button type="submit" class="btn btn-gold text-xs px-3 shadow-md">Search</button>
            </form>
            <button onclick="document.getElementById('new-student-modal').classList.remove('hidden')" class="btn btn-gold text-xs shadow-md shrink-0">
                <i class="fa-solid fa-plus"></i> + नया अभ्यर्थी जोड़ें
            </button>
        </div>
    </div>

    <div class="p-6 rounded-3xl panel space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" style="color: var(--text-main);">
                <thead class="uppercase font-bold" style="background-color: var(--bg-main); color: var(--text-muted); border-bottom: 1px solid var(--border-color);">
                    <tr>
                        <th class="p-3.5">ID</th>
                        <th class="p-3.5">अभ्यर्थी (Student Name)</th>
                        <th class="p-3.5">ईमेल (Email)</th>
                        <th class="p-3.5">मोबाइल</th>
                        <th class="p-3.5">Pro स्टेटस</th>
                        <th class="p-3.5">एक्शन</th>
                    </tr>
                </thead>
                <tbody class="font-medium">
                    @foreach($students as $st)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td class="p-3.5 font-mono" style="color: var(--text-muted);">#{{ $st->id }}</td>
                            <td class="p-3.5 font-bold" style="color: var(--text-main) !important;">{{ $st->name }}</td>
                            <td class="p-3.5" style="color: var(--text-muted);">{{ $st->email }}</td>
                            <td class="p-3.5" style="color: var(--text-muted);">{{ $st->phone ?: 'N/A' }}</td>
                            <td class="p-3.5">
                                @if($st->is_pro)
                                    <span class="px-2.5 py-0.5 rounded-full font-extrabold" style="background-color: var(--teal-soft); color: var(--teal); border: 1px solid var(--teal);">
                                        PRO UNLOCKED
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full font-bold" style="background-color: var(--bg-main); color: var(--text-muted); border: 1px solid var(--border-hard);">
                                        FREE TRIAL
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5 flex items-center gap-2">
                                <form action="{{ route('admin.students.toggle-pro', $st->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 rounded-lg text-[11px] font-bold border" style="background-color: var(--bg-main); border-color: var(--border-hard); color: var(--gold-deep);" title="Toggle Pro">
                                        <i class="fa-solid fa-bolt"></i>
                                    </button>
                                </form>
                                <button onclick="document.getElementById('edit-student-modal-{{ $st->id }}').classList.remove('hidden')" class="px-3 py-1 rounded-lg text-[11px] font-bold border" style="background-color: var(--bg-main); border-color: var(--border-hard); color: var(--teal);" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('admin.students.destroy', $st->id) }}" method="POST" onsubmit="return confirm('क्या आप वाकई इस छात्र को हटाना चाहते हैं?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 rounded-lg text-[11px] font-bold border" style="background-color: var(--rose-soft); border-color: var(--rose); color: var(--rose);" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Student Modal -->
                        <div id="edit-student-modal-{{ $st->id }}" class="fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
                            <div class="p-8 rounded-3xl panel max-w-lg w-full space-y-6">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-bold" style="color: var(--text-main) !important;">अभ्यर्थी संपादित करें</h3>
                                    <button onclick="document.getElementById('edit-student-modal-{{ $st->id }}').classList.add('hidden')" style="color: var(--text-muted);">
                                        <i class="fa-solid fa-xmark text-lg"></i>
                                    </button>
                                </div>

                                <form action="{{ route('admin.students.update', $st->id) }}" method="POST" class="space-y-4 text-xs">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="field">
                                        <label>Full Name</label>
                                        <input type="text" name="name" value="{{ $st->name }}" required>
                                    </div>
                                    <div class="field">
                                        <label>Email Address</label>
                                        <input type="email" name="email" value="{{ $st->email }}" required>
                                    </div>
                                    <div class="field">
                                        <label>Phone Number (Optional)</label>
                                        <input type="text" name="phone" value="{{ $st->phone }}">
                                    </div>
                                    <div class="field">
                                        <label>Password (Leave empty to keep current)</label>
                                        <input type="password" name="password" placeholder="New password...">
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
            {{ $students->links() }}
        </div>
    </div>
</div>

<!-- New Student Modal -->
<div id="new-student-modal" class="fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="p-8 rounded-3xl panel max-w-lg w-full space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold" style="color: var(--text-main) !important;">+ नया अभ्यर्थी जोड़ें</h3>
            <button onclick="document.getElementById('new-student-modal').classList.add('hidden')" style="color: var(--text-muted);">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.students.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            
            <div class="field">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="Rahul Kumar" required>
            </div>
            <div class="field">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="rahul@example.com" required>
            </div>
            <div class="field">
                <label>Phone Number (Optional)</label>
                <input type="text" name="phone" placeholder="9876543210">
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            
            <label class="flex items-center gap-2 cursor-pointer" style="justify-content: flex-start !important;">
                <input type="checkbox" name="is_pro">
                <span style="color: var(--text-muted);">Give PRO Access instantly</span>
            </label>

            <button type="submit" class="w-full btn btn-gold font-bold shadow-lg" style="margin-top: 20px;">
                छात्र सेव करें
            </button>
        </form>
    </div>
</div>
@endsection

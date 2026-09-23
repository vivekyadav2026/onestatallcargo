@extends('layouts.admin')

@section('title', 'Course Management - Testwise Admin Console')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 sm:p-8 rounded-3xl panel space-y-4 relative overflow-hidden">
        <div>
            <h1 class="text-2xl font-black" style="color: var(--text-main) !important;">Course & Exam Packages</h1>
            <p class="text-xs" style="color: var(--text-muted);">Manage different exams (e.g. MP Police, SSC) sold on the platform.</p>
        </div>

        <button onclick="document.getElementById('new-course-modal').classList.remove('hidden')" class="btn btn-gold text-xs shadow-md">
            <i class="fa-solid fa-plus"></i> Add New Course
        </button>
    </div>

    <!-- Course List Table -->
    <div class="p-6 rounded-3xl panel space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" style="color: var(--text-main);">
                <thead class="uppercase font-bold" style="background-color: var(--bg-main); color: var(--text-muted); border-bottom: 1px solid var(--border-color);">
                    <tr>
                        <th class="p-3.5">#ID</th>
                        <th class="p-3.5">Course Title</th>
                        <th class="p-3.5">Slug</th>
                        <th class="p-3.5">Pricing (₹)</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5">Actions</th>
                    </tr>
                </thead>
                <tbody class="font-medium">
                    @foreach($courses as $course)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td class="p-3.5 font-bold font-mono" style="color: var(--text-muted);">{{ sprintf('%02d', $course->id) }}</td>
                            <td class="p-3.5">
                                <span class="font-bold block" style="color: var(--text-main) !important;">{{ $course->title_hi }}</span>
                                <span class="text-[11px]" style="color: var(--text-muted);">{{ Str::limit($course->description_hi, 50) }}</span>
                            </td>
                            <td class="p-3.5 font-semibold" style="color: var(--text-muted);">{{ $course->slug }}</td>
                            <td class="p-3.5 font-bold" style="color: var(--theme-active);">
                                <span class="line-through text-gray-400 mr-1">₹{{ $course->price }}</span> ₹{{ $course->discounted_price }}
                            </td>
                            <td class="p-3.5">
                                @if($course->is_active)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-lg text-[10px]">Active</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-lg text-[10px]">Inactive</span>
                                @endif
                            </td>
                            <td class="p-3.5 flex gap-2">
                                <button onclick="editCourse({{ $course->id }}, '{{ addslashes($course->title_hi) }}', '{{ $course->slug }}', '{{ $course->price }}', '{{ $course->discounted_price }}', {{ $course->is_active ? 'true' : 'false' }}, '{{ addslashes($course->description_hi) }}')" class="px-2 py-1 rounded bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100">
                                    Edit
                                </button>
                                <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Delete this course permanently? This will remove all linked subjects and tests!');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-2 py-1 rounded bg-red-50 text-red-600 border border-red-200 hover:bg-red-100">Del</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $courses->links() }}
        </div>
    </div>
</div>

<!-- Modal Form for Adding New Course -->
<div id="new-course-modal" class="fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="p-8 rounded-3xl panel max-w-lg w-full space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold" style="color: var(--text-main) !important;">+ Add New Course</h3>
            <button onclick="document.getElementById('new-course-modal').classList.add('hidden')" style="color: var(--text-muted);">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.courses.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            
            <div class="field">
                <label>Course Title</label>
                <input type="text" name="title_hi" placeholder="e.g. SSC CGL Tier 1" required>
            </div>
            <div class="field">
                <label>URL Slug</label>
                <input type="text" name="slug" placeholder="e.g. ssc-cgl-tier-1" required>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="field">
                    <label>Regular Price (₹)</label>
                    <input type="number" name="price" required>
                </div>
                <div class="field">
                    <label>Discounted Price (₹)</label>
                    <input type="number" name="discounted_price" required>
                </div>
            </div>

            <div class="field">
                <label>Description</label>
                <textarea name="description_hi" rows="2" class="w-full border rounded px-3 py-2"></textarea>
            </div>
            
            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="is_active_new" value="1" checked>
                <label for="is_active_new" class="font-bold">Course is Active & Visible</label>
            </div>

            <button type="submit" class="w-full btn btn-gold font-bold shadow-lg mt-4">
                Save Course
            </button>
        </form>
    </div>
</div>

<!-- Modal Form for Editing Course -->
<div id="edit-course-modal" class="fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="p-8 rounded-3xl panel max-w-lg w-full space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold" style="color: var(--text-main) !important;">Edit Course</h3>
            <button onclick="document.getElementById('edit-course-modal').classList.add('hidden')" style="color: var(--text-muted);">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="edit-course-form" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            
            <div class="field">
                <label>Course Title</label>
                <input type="text" name="title_hi" id="edit_title" required>
            </div>
            <div class="field">
                <label>URL Slug</label>
                <input type="text" name="slug" id="edit_slug" required>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="field">
                    <label>Regular Price (₹)</label>
                    <input type="number" name="price" id="edit_price" required>
                </div>
                <div class="field">
                    <label>Discounted Price (₹)</label>
                    <input type="number" name="discounted_price" id="edit_discount" required>
                </div>
            </div>

            <div class="field">
                <label>Description</label>
                <textarea name="description_hi" id="edit_desc" rows="2" class="w-full border rounded px-3 py-2"></textarea>
            </div>
            
            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="edit_active" value="1">
                <label for="edit_active" class="font-bold">Course is Active & Visible</label>
            </div>

            <button type="submit" class="w-full btn btn-gold font-bold shadow-lg mt-4">
                Update Course
            </button>
        </form>
    </div>
</div>

<script>
    function editCourse(id, title, slug, price, discount, active, desc) {
        document.getElementById('edit-course-form').action = `/admin/courses/${id}`;
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_slug').value = slug;
        document.getElementById('edit_price').value = price;
        document.getElementById('edit_discount').value = discount;
        document.getElementById('edit_active').checked = active;
        document.getElementById('edit_desc').value = desc;
        document.getElementById('edit-course-modal').classList.remove('hidden');
    }
</script>
@endsection

@extends('layouts.student')
@section('title', 'My Courses - Testwise')

@section('content')
<div class="dash-head mb-6">
    <h1 class="text-2xl font-bold mb-1">My Courses</h1>
    <p class="text-sm" style="color: var(--text-muted);">Select a course to view its syllabus, mock tests, and your performance.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($courses as $course)
        @php
            $isEnrolled = $enrollments->contains('course_id', $course->id);
            $isPro = $user->isProFor($course->id);
        @endphp
        
        <div class="card rounded-2xl overflow-hidden hover:-translate-y-1 transition-all duration-300">
            <div class="h-32 p-5 relative flex flex-col justify-end" style="background: linear-gradient(135deg, #17233f, #2a3754);">
                @if($isEnrolled)
                    @if($isPro)
                        <div class="absolute top-4 right-4 bg-teal-500/20 backdrop-blur-sm px-2.5 py-1 rounded-md text-[11px] font-bold text-teal-300 border border-teal-500/30">
                            <i class="fa-solid fa-crown text-[var(--gold)]"></i> Pro Unlocked
                        </div>
                    @else
                        <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm px-2.5 py-1 rounded-md text-[11px] font-bold text-white border border-white/20">
                            <i class="fa-solid fa-eye text-yellow-300"></i> Free Preview
                        </div>
                    @endif
                @endif
                <div class="relative z-10">
                    <h3 class="text-lg font-extrabold leading-tight tracking-tight text-white" style="color: #ffffff !important;">{{ $course->title_hi }}</h3>
                    <p class="text-xs font-medium mt-1 text-slate-200" style="color: #e2e8f0 !important;"><i class="fa-solid fa-layer-group text-[var(--gold)]"></i> Multiple Subjects & Tests</p>
                </div>
            </div>
            
            <div class="p-5 flex flex-col gap-4">
                <div class="text-sm" style="color: var(--text-muted);">
                    {{ Str::limit($course->description, 80) }}
                </div>
                
                <form action="{{ route('student.switch-course') }}" method="POST" class="mt-auto pt-2">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <button type="submit" class="w-full btn btn-gold" style="justify-content: center; width: 100%;">
                        <span>{{ $isEnrolled ? 'Go to Course' : 'Enroll & Start' }}</span>
                        <i class="fa-solid fa-arrow-right ml-1"></i>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12 card border border-dashed rounded-xl">
            <i class="fa-solid fa-box-open text-4xl mb-3 opacity-30 text-[var(--text-main)]"></i>
            <h3 class="text-lg font-bold">No Courses Available</h3>
            <p class="text-sm mt-1" style="color: var(--text-muted);">There are currently no active courses.</p>
        </div>
    @endforelse
</div>
@endsection

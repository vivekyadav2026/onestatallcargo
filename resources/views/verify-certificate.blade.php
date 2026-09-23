@extends('layouts.app')

@section('title', 'Verify Certificate - Testwise MP Police GD 2026')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 space-y-8">
    
    <div class="text-center space-y-3">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl mx-auto font-bold border" style="background-color: rgba(217,154,43,0.1); color: var(--gold-deep); border-color: var(--gold);">
            <i class="fa-solid fa-certificate"></i>
        </div>
        <h1 class="text-3xl font-extrabold" style="color: var(--text-main) !important;">Official Certificate Verification</h1>
        <p class="text-sm" style="color: var(--text-muted);">Enter the Certificate ID to verify authentic MP Police GD 2026 completion credentials.</p>
    </div>

    <!-- Verification Search Form -->
    <div class="p-6 rounded-3xl shadow-xl max-w-xl mx-auto border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <form method="GET" action="{{ route('verify-certificate') }}" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="code" value="{{ request('code', 'TW-CERT-2026-GD-101') }}" placeholder="e.g. TW-CERT-2026-GD-101" required class="flex-grow px-4 py-3 rounded-xl border font-mono text-sm focus:outline-none" style="background-color: var(--bg-main); border-color: var(--border-hard); color: var(--text-main);">
            <button type="submit" class="btn btn-gold text-sm shadow-md shrink-0 flex items-center justify-center gap-2">
                <i class="fa-solid fa-magnifying-glass"></i> Verify Now
            </button>
        </form>
    </div>

    <!-- Search Result Card -->
    @if($searchCode)
        @if($certificate)
            <div class="p-8 rounded-3xl shadow-2xl space-y-6 max-w-2xl mx-auto relative overflow-hidden panel" style="border: 1px solid var(--teal);">
                <div class="absolute -right-8 -bottom-8 w-40 h-40 rounded-full blur-2xl" style="background-color: var(--teal-soft);"></div>

                <div class="flex items-center justify-between pb-6 border-b" style="border-color: var(--border-color);">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold" style="background-color: var(--teal-soft); color: var(--teal);">
                            <i class="fa-solid fa-seal-check text-xl"></i>
                        </div>
                        <div>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border" style="background-color: var(--teal-soft); color: var(--teal); border-color: var(--teal);">
                                VERIFIED GENUINE
                            </span>
                            <h3 class="text-sm font-bold mt-1" style="color: var(--text-main) !important;">Testwise Official Credentials</h3>
                        </div>
                    </div>
                    <span class="font-mono text-xs px-3 py-1 rounded-lg border" style="background-color: var(--bg-main); color: var(--text-muted); border-color: var(--border-hard);">
                        {{ $certificate->certificate_code }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 text-xs">
                    <div class="border-b sm:border-b-0 pb-3 sm:pb-0" style="border-color: var(--border-color);">
                        <span class="block mb-1" style="color: var(--text-muted);">Candidate Name</span>
                        <span class="text-base font-bold" style="color: var(--text-main) !important;">{{ $certificate->user->name ?? 'Rahul Sharma' }}</span>
                    </div>
                    <div class="border-b sm:border-b-0 pb-3 sm:pb-0" style="border-color: var(--border-color);">
                        <span class="block mb-1" style="color: var(--text-muted);">Course Title</span>
                        <span class="text-base font-bold" style="color: var(--text-main) !important;">{{ $certificate->course_name }}</span>
                    </div>
                    <div class="border-b sm:border-b-0 pb-3 sm:pb-0" style="border-color: var(--border-color);">
                        <span class="block mb-1" style="color: var(--text-muted);">Issue Date</span>
                        <span class="font-semibold" style="color: var(--text-main) !important;">{{ \Carbon\Carbon::parse($certificate->issue_date)->format('d M Y') }}</span>
                    </div>
                    <div>
                        <span class="block mb-1" style="color: var(--text-muted);">Overall Accuracy / Score</span>
                        <span class="font-bold" style="color: var(--teal);">{{ $certificate->score_achieved }}% Score</span>
                    </div>
                </div>

                <div class="p-4 rounded-2xl flex items-center justify-between text-xs border" style="background-color: var(--bg-main); border-color: var(--border-hard);">
                    <span style="color: var(--text-muted);">Issuer Authority: Testwise Exam Analytics Engine</span>
                    <a href="{{ route('student.certificate') }}" class="font-bold hover:underline" style="color: var(--gold-deep);">Download Copy →</a>
                </div>
            </div>
        @else
            <div class="p-8 rounded-3xl text-center space-y-3 max-w-md mx-auto panel" style="border: 1px solid var(--rose-soft);">
                <i class="fa-solid fa-circle-xmark text-4xl" style="color: var(--rose);"></i>
                <h3 class="text-lg font-bold" style="color: var(--text-main) !important;">Certificate Not Found</h3>
                <p class="text-xs" style="color: var(--text-muted);">No matching record found for "{{ $searchCode }}". Please verify the code and try again.</p>
            </div>
        @endif
    @endif
</div>
@endsection

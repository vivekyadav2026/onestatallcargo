@extends('layouts.student')

@section('title', 'Official Certificate - Testwise MP Police GD 2026')

@section('content')
<div class="space-y-8 max-w-4xl mx-auto">
    <div class="text-center space-y-2">
        <span class="px-3 py-1 rounded-full text-xs font-bold" style="background-color: rgba(217,154,43,0.1); color: var(--gold-deep); border: 1px solid var(--gold);">
            OFFICIAL CERTIFICATE OF COMPLETION
        </span>
        <h1 class="text-3xl font-black" style="color: var(--text-main) !important;">पाठ्यक्रम पूर्णता प्रमाणपत्र</h1>
        <p class="text-xs" style="color: var(--text-muted);">Testwise Exam Analytics Engine द्वारा सत्यापित डिजिटल प्रमाणपत्र</p>
    </div>

    <!-- Printable Official Certificate Card -->
    <div class="p-8 sm:p-12 rounded-3xl space-y-8 relative overflow-hidden panel" style="background-color: var(--bg-card); border: 2px solid var(--gold-deep); box-shadow: 0 10px 30px rgba(217, 154, 43, 0.1);">
        <div class="flex items-center justify-between pb-6 border-b" style="border-color: var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-bold text-2xl" style="background-color: rgba(217, 154, 43, 0.1); color: var(--gold-deep);">
                    <i class="fa-solid fa-certificate"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black tracking-tight" style="color: var(--theme-bg);">Testwise EdTech India</h2>
                    <p class="text-xs font-bold" style="color: var(--gold-deep);">आधिकारिक डिजिटल सत्यापन प्रमाणपत्र</p>
                </div>
            </div>
            <div class="text-right font-mono text-xs" style="color: var(--text-muted);">
                <span>CERT ID:</span>
                <span class="font-bold block" style="color: var(--text-main) !important;">{{ $certificate->certificate_code }}</span>
            </div>
        </div>

        <div class="text-center space-y-4 py-4">
            <p class="text-xs uppercase tracking-widest" style="color: var(--text-muted);">THIS IS TO CERTIFY THAT</p>
            <h2 class="text-3xl sm:text-4xl font-black" style="color: var(--theme-bg);">
                {{ $user->name }}
            </h2>
            <p class="text-xs sm:text-sm max-w-xl mx-auto leading-relaxed" style="color: var(--text-muted);">
                has successfully completed the comprehensive preparation batch and all 32 chapter CBT examinations for 
                <strong style="color: var(--text-main) !important;">MP Police Constable GD 2026 Recruitment Examination</strong>.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-6 border-t text-center text-xs" style="border-color: var(--border-color);">
            <div class="p-2">
                <span class="block mb-1" style="color: var(--text-muted);">Issue Date</span>
                <span class="font-bold" style="color: var(--text-main) !important;">{{ \Carbon\Carbon::parse($certificate->issue_date)->format('d M Y') }}</span>
            </div>
            <div class="p-2 border-t sm:border-t-0 sm:border-l" style="border-color: var(--border-color);">
                <span class="block mb-1" style="color: var(--text-muted);">Overall Performance</span>
                <span class="font-bold" style="color: var(--teal);">{{ $certificate->score_achieved }}% Score</span>
            </div>
            <div class="p-2 border-t sm:border-t-0 sm:border-l" style="border-color: var(--border-color);">
                <span class="block mb-1" style="color: var(--text-muted);">Verification Status</span>
                <span class="font-bold" style="color: var(--theme-bg);">VERIFIED GENUINE</span>
            </div>
        </div>
    </div>

    <div class="flex justify-center gap-4">
        <button onclick="window.print()" class="btn btn-gold text-xs shadow-lg">
            <i class="fa-solid fa-print"></i> Print / Download PDF Certificate
        </button>
        <a href="{{ route('verify-certificate', ['code' => $certificate->certificate_code]) }}" target="_blank" class="btn btn-secondary text-xs">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> Open Verification Page
        </a>
    </div>
</div>
@endsection

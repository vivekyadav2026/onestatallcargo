@extends('layouts.app')

@section('title', 'Exam Pattern & Syllabus - MP Police GD 2026')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 space-y-10">
    <div class="text-center space-y-3">
        <h1 class="text-3xl font-extrabold" style="color: var(--text-main) !important;">MP Police Constable GD 2026 Exam Pattern</h1>
        <p class="text-sm" style="color: var(--text-muted);">मध्य प्रदेश कर्मचारी चयन मंडल (MPESB) द्वारा घोषित नवीनतम परीक्षा पैटर्न</p>
    </div>

    <!-- Exam Pattern Table -->
    <div class="p-8 rounded-3xl panel space-y-6">
        <h3 class="text-lg font-bold" style="color: var(--text-main) !important;">लिखित परीक्षा अंक योजना (Written Exam Blueprint)</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" style="color: var(--text-main);">
                <thead class="uppercase font-bold" style="background-color: var(--bg-main); color: var(--text-muted); border-bottom: 1px solid var(--border-color);">
                    <tr>
                        <th class="p-3.5">विषय (Subject)</th>
                        <th class="p-3.5">कुल प्रश्न (Questions)</th>
                        <th class="p-3.5">कुल अंक (Marks)</th>
                        <th class="p-3.5">समय सीमा (Duration)</th>
                    </tr>
                </thead>
                <tbody class="font-medium">
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td class="p-3.5 font-bold" style="color: var(--text-main) !important;">सामान्य ज्ञान एवं समसामयिक विषय (GK & CA)</td>
                        <td class="p-3.5">40 प्रश्न</td>
                        <td class="p-3.5 font-bold" style="color: var(--theme-active);">40 अंक</td>
                        <td class="p-3.5" rowspan="3">120 मिनट (2 घंटे)</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td class="p-3.5 font-bold" style="color: var(--text-main) !important;">तार्किक क्षमता एवं मानसिक अभिरुचि (Reasoning)</td>
                        <td class="p-3.5">30 प्रश्न</td>
                        <td class="p-3.5 font-bold" style="color: var(--gold-deep);">30 अंक</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td class="p-3.5 font-bold" style="color: var(--text-main) !important;">विज्ञान एवं सरल अंकगणित (Science & Maths)</td>
                        <td class="p-3.5">30 प्रश्न</td>
                        <td class="p-3.5 font-bold" style="color: var(--teal);">30 अंक</td>
                    </tr>
                    <tr class="font-bold" style="background-color: var(--bg-main); color: var(--text-main) !important;">
                        <td class="p-3.5">कुल योग (TOTAL)</td>
                        <td class="p-3.5">100 प्रश्न</td>
                        <td class="p-3.5" style="color: var(--teal);">100 अंक</td>
                        <td class="p-3.5">120 मिनट</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="p-4 rounded-2xl text-xs space-y-1" style="background-color: rgba(217,154,43,0.1); border: 1px solid var(--gold); color: var(--gold-deep);">
            <div class="font-bold"><i class="fa-solid fa-circle-info mr-1"></i> मुख्य बातें:</div>
            <ul class="list-disc list-inside space-y-0.5" style="color: var(--text-main);">
                <li>परीक्षा ऑनलाइन कंप्यूटर बेस्ड (CBT) माध्यम में आयोजित की जाएगी।</li>
                <li>प्रत्येक सही उत्तर के लिए 1 अंक दिया जाएगा।</li>
                <li>कोई ऋणात्मक अंकन (No Negative Marking) नहीं है।</li>
            </ul>
        </div>
    </div>
</div>
@endsection

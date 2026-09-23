@extends('layouts.admin')

@section('title', 'Admin Dashboard - Testwise Console')

@section('content')
<div class="space-y-6">
    
    <!-- Admin Hero Banner -->
    <div class="p-6 md:p-8 rounded-[1.5rem] bg-gradient-to-r from-gray-900 to-[#1E293B] text-white space-y-4 shadow-xl border border-gray-800 relative overflow-hidden">
        <!-- Decor -->
        <div class="absolute right-0 top-0 w-64 h-64 bg-[var(--gold)] mix-blend-multiply opacity-20 filter blur-3xl rounded-full"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-widest text-[var(--gold)] mb-2">
                <span class="w-2 h-2 rounded-full bg-[var(--gold)] animate-pulse"></span>
                CENTRAL ADMIN ENGINE
            </div>
            
            <h1 class="text-2xl md:text-3xl font-extrabold text-white">Course Management & Analytics</h1>
            <p class="text-sm text-gray-300 mt-2 max-w-2xl">Manage courses, subjects, chapters, questions, and view global student performance metrics.</p>

            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center gap-3 pt-6">
                <a href="{{ route('admin.courses') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[var(--gold)] text-gray-900 shadow-md hover:bg-[var(--gold-deep)] transition-colors">
                    <i class="fa-solid fa-graduation-cap mr-1"></i> Manage Courses
                </a>
                <a href="{{ route('admin.chapters') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-white/10 border border-white/20 hover:bg-white/20 transition-colors">
                    <i class="fa-solid fa-plus mr-1"></i> Add Chapter
                </a>
                <a href="{{ route('admin.questions') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-white/10 border border-white/20 hover:bg-white/20 transition-colors">
                    <i class="fa-solid fa-folder-plus mr-1"></i> Question Bank
                </a>
            </div>
        </div>
    </div>

    <!-- 5 KPI Stat Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
        
        <!-- Metric 1: Students -->
        <div class="p-5 rounded-2xl bg-white border border-gray-200 shadow-sm space-y-2 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between text-gray-500">
                <span class="text-[10px] font-extrabold uppercase tracking-wider">Total Students</span>
                <i class="fa-solid fa-users text-blue-500"></i>
            </div>
            <div class="text-3xl font-black text-gray-900">{{ $totalStudents }}</div>
            <div class="text-[11px] font-medium text-gray-400">{{ $proStudents }} Pro, {{ $freeStudents }} Free</div>
        </div>

        <!-- Metric 2: Chapters -->
        <div class="p-5 rounded-2xl bg-white border border-gray-200 shadow-sm space-y-2 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between text-gray-500">
                <span class="text-[10px] font-extrabold uppercase tracking-wider">Total Chapters</span>
                <i class="fa-solid fa-book-open text-green-500"></i>
            </div>
            <div class="text-3xl font-black text-gray-900">{{ $totalChapters }}</div>
            <div class="text-[11px] font-medium text-gray-400">Across all active courses</div>
        </div>

        <!-- Metric 3: CBT Mocks -->
        <div class="p-5 rounded-2xl bg-white border border-gray-200 shadow-sm space-y-2 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between text-gray-500">
                <span class="text-[10px] font-extrabold uppercase tracking-wider">Mock Tests</span>
                <i class="fa-solid fa-clock text-purple-500"></i>
            </div>
            <div class="text-3xl font-black text-gray-900">{{ $totalMockTests }}</div>
            <div class="text-[11px] font-medium text-gray-400">Full-length assessments</div>
        </div>

        <!-- Metric 4: Attempts -->
        <div class="p-5 rounded-2xl bg-white border border-gray-200 shadow-sm space-y-2 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between text-gray-500">
                <span class="text-[10px] font-extrabold uppercase tracking-wider">Total Attempts</span>
                <i class="fa-solid fa-chart-line text-red-500"></i>
            </div>
            <div class="text-3xl font-black text-gray-900">7,923</div>
            <div class="text-[11px] font-medium text-gray-400">Platform average: 75%</div>
        </div>

        <!-- Metric 5: Revenue -->
        <div class="p-5 rounded-2xl bg-white border border-gray-200 shadow-sm space-y-2 hover:shadow-md transition-shadow col-span-2 md:col-span-4 lg:col-span-1">
            <div class="flex items-center justify-between text-gray-500">
                <span class="text-[10px] font-extrabold uppercase tracking-wider">Revenue</span>
                <i class="fa-solid fa-indian-rupee-sign text-[var(--gold)]"></i>
            </div>
            <div class="text-3xl font-black text-gray-900">₹{{ number_format($totalRevenue) }}</div>
            <div class="text-[11px] font-medium text-gray-400">{{ $totalPaymentsCount }} Successful Payments</div>
        </div>
    </div>

    <!-- Main Grid: Mock Performance Table & Platform Health Widget -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Column: Top Courses -->
        <div class="lg:col-span-8 space-y-6">
            <div class="p-6 rounded-3xl bg-white border border-gray-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b pb-4 border-gray-100">
                    <div>
                        <h3 class="font-extrabold text-base text-gray-900">Recent Exam Analytics</h3>
                        <p class="text-xs text-gray-500 mt-1">Average scores across the latest mock tests</p>
                    </div>
                    <a href="{{ route('admin.mock-tests') }}" class="text-xs font-bold text-[var(--gold-deep)] hover:underline">View All Mocks</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 border-b border-gray-100">
                                <th class="pb-3 font-medium">Exam Name</th>
                                <th class="pb-3 font-medium">Attempts</th>
                                <th class="pb-3 font-medium">Avg Score</th>
                                <th class="pb-3 font-medium">Top Score</th>
                                <th class="pb-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-gray-600 font-medium">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4">SSC CGL Tier 1 Full Mock 1</td>
                                <td class="py-4">1,204</td>
                                <td class="py-4"><span class="px-2 py-1 rounded bg-yellow-50 text-yellow-700 text-xs font-bold">64.5%</span></td>
                                <td class="py-4">98.0%</td>
                                <td class="py-4"><span class="w-2 h-2 inline-block rounded-full bg-green-500 mr-2"></span>Active</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4">MP Patwari Mock 1</td>
                                <td class="py-4">856</td>
                                <td class="py-4"><span class="px-2 py-1 rounded bg-green-50 text-green-700 text-xs font-bold">71.2%</span></td>
                                <td class="py-4">94.5%</td>
                                <td class="py-4"><span class="w-2 h-2 inline-block rounded-full bg-green-500 mr-2"></span>Active</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4">MP Police GD Mock 1</td>
                                <td class="py-4">3,492</td>
                                <td class="py-4"><span class="px-2 py-1 rounded bg-red-50 text-red-700 text-xs font-bold">58.9%</span></td>
                                <td class="py-4">99.0%</td>
                                <td class="py-4"><span class="w-2 h-2 inline-block rounded-full bg-green-500 mr-2"></span>Active</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4">MP Police GD Mock 2</td>
                                <td class="py-4">2,100</td>
                                <td class="py-4"><span class="px-2 py-1 rounded bg-yellow-50 text-yellow-700 text-xs font-bold">62.1%</span></td>
                                <td class="py-4">95.0%</td>
                                <td class="py-4"><span class="w-2 h-2 inline-block rounded-full bg-gray-300 mr-2"></span>Draft</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Weak Topics Widget -->
        <div class="lg:col-span-4 space-y-6">
            <div class="p-6 rounded-3xl bg-white border border-gray-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b pb-4 border-gray-100">
                    <h3 class="font-extrabold text-base text-gray-900">Platform Health</h3>
                    <i class="fa-solid fa-heart-pulse text-red-500"></i>
                </div>

                <div class="space-y-5 pt-2">
                    <!-- Progress Bar 1 -->
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1.5 text-gray-700">
                            <span>Server Uptime</span>
                            <span class="text-green-500">99.9%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="bg-green-500 h-1.5 rounded-full" style="width: 99.9%"></div>
                        </div>
                    </div>

                    <!-- Progress Bar 2 -->
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1.5 text-gray-700">
                            <span>Database Load</span>
                            <span class="text-yellow-500">42%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="bg-yellow-500 h-1.5 rounded-full" style="width: 42%"></div>
                        </div>
                    </div>
                    
                    <!-- Progress Bar 3 -->
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1.5 text-gray-700">
                            <span>Storage Usage</span>
                            <span class="text-blue-500">18%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="bg-blue-500 h-1.5 rounded-full" style="width: 18%"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <button class="w-full py-2.5 rounded-xl text-xs font-bold border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">
                        View Detailed Logs
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

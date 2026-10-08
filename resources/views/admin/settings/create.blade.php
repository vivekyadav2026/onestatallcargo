@extends('layouts.admin')
@section('title', 'Add Setting - OneStall Cargo')
@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.settings.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-500 hover:text-gray-800 hover:shadow-sm transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Add Global Setting</h1>
            <p class="text-sm text-gray-500 mt-1">Create a new system configuration variable.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden p-8 max-w-3xl">
        <form action="{{ route('admin.settings.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Configuration Group</label>
                    <input type="text" name="group" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:outline-none focus:bg-white focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition" placeholder="e.g., api_keys, system, pricing">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Key Variable</label>
                    <input type="text" name="key" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold font-mono text-gray-900 focus:outline-none focus:bg-white focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition" placeholder="e.g., delhivery_token">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Configuration Value</label>
                <textarea name="value" rows="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-mono text-gray-800 focus:outline-none focus:bg-white focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition" placeholder="Enter the value here..."></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.settings.index') }}" class="px-6 py-2.5 text-sm font-bold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition shadow-sm">Cancel</a>
                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-green-600 rounded-xl shadow-md transition hover:bg-green-700 flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Create Setting
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
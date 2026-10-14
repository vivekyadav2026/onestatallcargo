@extends('layouts.admin')

@section('title', 'Add API Documentation Endpoint - OneStall Cargo')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Add API Endpoint</h1>
            <p class="text-sm text-gray-500 mt-1">Define an API endpoint specification and documentation example.</p>
        </div>
        <a href="{{ route('admin.api_docs.index') }}" class="px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-100 font-bold text-sm transition">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm" role="alert">
            <p class="font-bold mb-1">Please fix the following errors:</p>
            <ul class="list-disc pl-5 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.api_docs.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Endpoint Key <span class="text-red-500">*</span></label>
                    <input type="text" name="endpoint_key" value="{{ old('endpoint_key') }}" required placeholder="e.g. check_rates" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Calculate Rates" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">HTTP Method <span class="text-red-500">*</span></label>
                    <select name="method" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">
                        @foreach(['GET', 'POST', 'PUT', 'PATCH', 'DELETE'] as $m)
                            <option value="{{ $m }}" {{ old('method', 'POST') === $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Path <span class="text-red-500">*</span></label>
                    <input type="text" name="path" value="{{ old('path', '/api/v1/') }}" required placeholder="/api/v1/shipments" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)] font-mono">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="3" required placeholder="Describe what this endpoint does..." class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">cURL Example</label>
                <textarea name="curl_example" rows="4" placeholder="curl -X POST https://api.onestallcargo.com/api/v1/..." class="w-full border border-gray-200 rounded-lg px-3 py-2 text-xs font-mono bg-gray-50 focus:outline-none focus:border-[var(--gold)]">{{ old('curl_example') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">JSON Response</label>
                <textarea name="json_response" rows="4" placeholder="{&#10;  &quot;status&quot;: &quot;success&quot;&#10;}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-xs font-mono bg-gray-50 focus:outline-none focus:border-[var(--gold)]">{{ old('json_response') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">
                </div>
                <div class="flex items-center pt-5">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-bold text-gray-700">Active / Published</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.api_docs.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-100 font-bold text-sm transition">Cancel</a>
            <button type="submit" class="px-8 py-2.5 bg-gray-900 text-white rounded-xl hover:bg-black font-bold text-sm shadow-md transition">Save Endpoint</button>
        </div>
    </form>
</div>
@endsection

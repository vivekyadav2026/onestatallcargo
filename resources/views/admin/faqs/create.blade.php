@extends('layouts.admin')

@section('title', 'Add FAQ - OneStall Cargo')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
    <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Add FAQ</h1>
    <form action="{{ route('admin.faqs.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Question</label>
            <input type="text" name="question" class="w-full px-4 py-2 bg-gray-50 border rounded-xl" required>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Answer</label>
            <textarea name="answer" rows="4" class="w-full px-4 py-2 bg-gray-50 border rounded-xl" required></textarea>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Sort Order</label>
            <input type="number" name="sort_order" value="0" class="w-full px-4 py-2 bg-gray-50 border rounded-xl">
        </div>
        <div class="flex items-center">
            <input type="checkbox" name="is_active" value="1" checked class="mr-2">
            <label class="text-sm font-bold text-gray-700">Active</label>
        </div>
        <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white font-bold rounded-xl">Save</button>
    </form>
</div>
@endsection

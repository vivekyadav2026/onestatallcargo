@extends('layouts.admin')

@section('title', 'Add Service - OneStall Cargo')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
    <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Add Service</h1>
    <form action="{{ route('admin.services.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Title</label>
            <input type="text" name="title" class="w-full px-4 py-2 bg-gray-50 border rounded-xl" required>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="4" class="w-full px-4 py-2 bg-gray-50 border rounded-xl" required></textarea>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Icon (FontAwesome classes)</label>
            <input type="text" name="icon" class="w-full px-4 py-2 bg-gray-50 border rounded-xl">
        </div>
        <div class="flex items-center">
            <input type="checkbox" name="is_active" value="1" checked class="mr-2">
            <label class="text-sm font-bold text-gray-700">Active</label>
        </div>
        <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white font-bold rounded-xl">Save</button>
    </form>
</div>
@endsection

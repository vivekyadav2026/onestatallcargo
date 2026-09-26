@extends('layouts.admin')
@section('title', 'Global Settings - OneStall Cargo')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Global Settings</h1>
    <a href="{{ route('admin.settings.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Add Setting</a>
</div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
                @endif
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-gray-700 text-xs uppercase">
                            <tr>
                                <th class="px-4 py-3">Group</th>
                                <th class="px-4 py-3">Key</th>
                                <th class="px-4 py-3">Value</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($settings as $item)
                                <tr class="border-b">
                                    <td class="px-4 py-3"><span class="px-2 py-1 bg-gray-200 rounded text-xs">{{ $item->group }}</span></td>
                                    <td class="px-4 py-3 font-bold">{{ $item->key }}</td>
                                    <td class="px-4 py-3 max-w-xs truncate">{{ $item->value }}</td>
                                    <td class="px-4 py-3 flex gap-2">
                                        <a href="{{ route('admin.settings.edit', $item->id) }}" class="text-blue-600">Edit</a>
                                        <form action="{{ route('admin.settings.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">No settings found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
@extends('layouts.admin')
@section('title', 'Global Settings - OneStall Cargo')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Global Configurations</h1>
            <p class="text-sm text-gray-500 mt-1">Manage system-wide environment variables and API tokens.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.settings.create') }}" class="btn btn-primary text-sm flex items-center gap-2 px-5 py-2.5 bg-[#4338ca] text-white rounded-xl font-bold hover:bg-indigo-700 transition shadow-sm"><i class="fa-solid fa-plus"></i> Add New Variable</a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 font-bold rounded-xl border border-green-200 text-sm">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <th class="px-6 py-4">Group</th>
                        <th class="px-6 py-4">Configuration Key</th>
                        <th class="px-6 py-4">Value</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700">
                    @forelse($settings as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-extrabold uppercase tracking-widest">{{ $item->group }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-mono font-bold text-indigo-700 text-xs">{{ $item->key }}</div>
                            </td>
                            <td class="px-6 py-4 max-w-xs truncate font-mono text-gray-500 text-xs">
                                {{ Str::limit($item->value, 40) }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.settings.edit', $item->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.settings.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('WARNING: Deleting this configuration may break system features! Continue?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-gears text-4xl mb-3 text-gray-200"></i>
                                <p class="font-bold">No system settings found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
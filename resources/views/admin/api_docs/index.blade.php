@extends('layouts.admin')

@section('title', 'API Documentation Management - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">API Documentation Management</h1>
            <p class="text-sm text-gray-500 mt-1">Manage API documentation endpoints displayed to developers and integrations.</p>
        </div>
        <a href="{{ route('admin.api_docs.create') }}" class="px-5 py-2.5 bg-[var(--gold)] text-gray-900 font-bold rounded-xl shadow-sm hover:bg-yellow-500 transition text-sm flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Add API Endpoint
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm font-semibold flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <th class="px-6 py-4">Method & Path</th>
                        <th class="px-6 py-4">Title / Key</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($docs as $doc)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded text-xs font-black uppercase mr-2 
                                    {{ $doc->method === 'GET' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $doc->method === 'POST' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $doc->method === 'PUT' || $doc->method === 'PATCH' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $doc->method === 'DELETE' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ $doc->method }}
                                </span>
                                <code class="font-mono text-xs text-gray-800 font-semibold">{{ $doc->path }}</code>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-900">{{ $doc->title }}</p>
                                <p class="text-xs text-gray-400 font-mono">{{ $doc->endpoint_key }}</p>
                            </td>
                            <td class="px-6 py-4 max-w-xs truncate text-gray-600 text-xs">
                                {{ $doc->description }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $doc->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $doc->is_active ? 'Active' : 'Draft' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.api_docs.edit', $doc) }}" class="p-2 text-gray-400 hover:text-blue-600 transition" title="Edit">
                                        <i class="fa-solid fa-pen text-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.api_docs.destroy', $doc) }}" method="POST" onsubmit="return confirm('Delete this API doc entry?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition" title="Delete">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">
                                <i class="fa-solid fa-code text-3xl mb-2 text-gray-300"></i>
                                <p>No API documentation entries created yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

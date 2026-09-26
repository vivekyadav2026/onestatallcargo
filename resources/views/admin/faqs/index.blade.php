@extends('layouts.admin')

@section('title', 'FAQs - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-extrabold text-gray-900">FAQs</h1>
        <a href="{{ route('admin.faqs.create') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[var(--gold)] text-gray-900 shadow-md hover:bg-[var(--gold-deep)]">Add FAQ</a>
    </div>
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-x-auto">
                        <div class="overflow-x-auto w-full">
<table class="w-full text-left text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 font-bold text-gray-700">Question</th>
                    <th class="px-6 py-4 font-bold text-gray-700">Sort Order</th>
                    <th class="px-6 py-4 font-bold text-gray-700">Status</th>
                    <th class="px-6 py-4 font-bold text-gray-700 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($faqs as $faq)
                <tr class="border-b border-gray-50">
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $faq->question }}</td>
                    <td class="px-6 py-4">{{ $faq->sort_order }}</td>
                    <td class="px-6 py-4">{{ $faq->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.faqs.edit', $faq) }}" class="text-blue-600 font-bold text-xs uppercase">Edit</a>
                        <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 font-bold text-xs uppercase">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
</div>
    </div>
</div>
@endsection

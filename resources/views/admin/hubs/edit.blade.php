@extends('layouts.admin')

@section('title', 'Edit Hub - OneStall Cargo')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.hubs.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Edit Hub: {{ $hub->hub_code }}</h1>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 text-red-700 border border-red-200 font-bold text-sm">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <form action="{{ route('admin.hubs.update', $hub->id) }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Name / Branch</label>
                    <input type="text" name="name" value="{{ $hub->name }}" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Base City</label>
                        <input type="text" name="city" value="{{ $hub->city }}" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Service Pincode</label>
                        <input type="text" name="pincode" value="{{ $hub->pincode }}" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Daily Capacity (Parcels)</label>
                    <input type="number" name="capacity" value="{{ $hub->capacity }}" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Assign Franchise Manager</label>
                    <select name="manager_id" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                        <option value="">-- No Manager Assigned --</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}" {{ $hub->manager_id == $manager->id ? 'selected' : '' }}>{{ $manager->name }} ({{ $manager->email }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.hubs.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-700 font-bold hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gray-900 text-white font-bold hover:bg-black transition shadow-md">Update Hub</button>
            </div>
        </form>
    </div>
</div>
@endsection

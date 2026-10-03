@extends('layouts.admin')
@section('title', 'Edit Testimonial - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Edit Testimonial</h1>
            <p class="text-sm text-gray-500 mt-1">Update existing client review.</p>
        </div>
        <a href="{{ route('admin.testimonials.index') }}" class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-xs font-bold hover:bg-gray-50 flex items-center gap-2 shadow-sm transition"><i class="fa-solid fa-arrow-left"></i> Back to List</a>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden p-6 md:p-8 max-w-3xl">
        <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Client Name</label>
                    <input type="text" name="client_name" value="{{ $testimonial->client_name }}" required placeholder="e.g. John Doe" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:border-[var(--gold)] focus:ring-1 focus:ring-[var(--gold)] outline-none transition shadow-sm placeholder:text-gray-400">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Company <span class="text-gray-400 font-medium normal-case">(Optional)</span></label>
                    <input type="text" name="company" value="{{ $testimonial->company }}" placeholder="e.g. Acme Corp" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:border-[var(--gold)] focus:ring-1 focus:ring-[var(--gold)] outline-none transition shadow-sm placeholder:text-gray-400">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Review Content</label>
                <textarea name="content" required rows="4" placeholder="What did the client say about your service?" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:border-[var(--gold)] focus:ring-1 focus:ring-[var(--gold)] outline-none transition shadow-sm placeholder:text-gray-400">{{ $testimonial->content }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-end">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Rating (1-5)</label>
                    <div class="relative">
                        <input type="number" name="rating" min="1" max="5" value="{{ $testimonial->rating }}" required class="w-full pl-9 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:border-[var(--gold)] focus:ring-1 focus:ring-[var(--gold)] outline-none transition shadow-sm font-bold text-[var(--gold-deep)]">
                        <i class="fa-solid fa-star absolute left-3 top-1/2 -translate-y-1/2 text-[var(--gold)] text-xs"></i>
                    </div>
                </div>
                
                <div class="pb-2.5">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative flex items-center justify-center">
                            <input type="checkbox" name="is_active" value="1" {{ $testimonial->is_active ? 'checked' : '' }} class="peer sr-only">
                            <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                        </div>
                        <span class="text-sm font-bold text-gray-700 group-hover:text-gray-900 transition">Publish Immediately</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 mt-2 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.testimonials.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-bold rounded-xl text-sm hover:bg-gray-50 transition shadow-sm">Cancel</a>
                <button type="submit" class="px-8 py-2.5 bg-gray-900 hover:bg-black text-white font-bold rounded-xl text-sm transition shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-arrows-rotate"></i> Update Testimonial
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
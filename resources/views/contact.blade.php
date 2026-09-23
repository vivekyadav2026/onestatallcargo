@extends('layouts.app')

@section('title', 'Contact Us - OneStall Cargo Logistics Network GD 2026')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 space-y-10">
    <div class="text-center space-y-3">
        <h1 class="text-3xl font-extrabold" style="color: var(--text-main) !important;">Help & Support Center</h1>
        <p class="text-sm" style="color: var(--text-muted);">Have questions about Logistics Network GD 2026 course access? Write to us.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="p-8 rounded-3xl panel space-y-6">
            <h3 class="text-lg font-bold" style="color: var(--text-main) !important;">Contact Form</h3>

            <form action="{{ route('contact') }}" method="GET" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold mb-1" style="color: var(--text-muted);">Your Name</label>
                    <input type="text" required placeholder="e.g. Rahul Sharma" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none" style="background-color: var(--bg-main); border-color: var(--border-hard); color: var(--text-main);">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1" style="color: var(--text-muted);">Email Address</label>
                    <input type="email" required placeholder="name@example.com" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none" style="background-color: var(--bg-main); border-color: var(--border-hard); color: var(--text-main);">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1" style="color: var(--text-muted);">Message</label>
                    <textarea rows="4" required placeholder="How can we help you?" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none" style="background-color: var(--bg-main); border-color: var(--border-hard); color: var(--text-main);"></textarea>
                </div>
                <button type="submit" class="w-full btn btn-gold text-sm shadow-md transition">
                    Send Message
                </button>
            </form>
        </div>

        <div class="p-8 rounded-3xl panel space-y-6 flex flex-col justify-between">
            <div class="space-y-4">
                <h3 class="text-lg font-bold" style="color: var(--text-main) !important;">Direct Contacts</h3>
                <p class="text-xs" style="color: var(--text-muted);">Our support team is available Monday to Saturday, 9 AM to 7 PM IST.</p>

                <div class="space-y-3 pt-2 text-xs">
                    <div class="p-3.5 rounded-2xl flex items-center gap-3 border" style="background-color: var(--bg-main); border-color: var(--border-hard);">
                        <i class="fa-solid fa-envelope text-base" style="color: var(--theme-active);"></i>
                        <div>
                            <span class="block text-[10px]" style="color: var(--text-muted);">EMAIL SUPPORT</span>
                            <span class="font-bold" style="color: var(--text-main) !important;">support@OneStall Cargo.in</span>
                        </div>
                    </div>
                    <div class="p-3.5 rounded-2xl flex items-center gap-3 border" style="background-color: var(--bg-main); border-color: var(--border-hard);">
                        <i class="fa-solid fa-phone text-base" style="color: var(--teal);"></i>
                        <div>
                            <span class="block text-[10px]" style="color: var(--text-muted);">CALL CENTER</span>
                            <span class="font-bold" style="color: var(--text-main) !important;">+91 98765 43210</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 rounded-2xl text-xs" style="background-color: rgba(217,154,43,0.1); border: 1px solid var(--gold); color: var(--gold-deep);">
                <span class="font-bold">OneStall Cargo EdTech Center</span><br>
                Bhopal, Madhya Pradesh, India - 462001
            </div>
        </div>
    </div>
</div>
@endsection


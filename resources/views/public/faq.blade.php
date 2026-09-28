@extends('layouts.public')
@section('title', "Frequently Asked Questions (FAQ) | OneStall Cargo")

@section('content')
<div class="bg-gray-50/50 py-10 md:py-12" x-data="faqApp()">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-block px-3 py-0.5 rounded-full border border-blue-200 bg-blue-50 text-brand-navy text-[10px] font-bold mb-3 uppercase tracking-wider">
            Help &amp; FAQs
        </div>
        <h1 class="text-2xl md:text-4xl font-extrabold text-brand-navy leading-tight mb-3">
            Frequently Asked <span class="text-brand-red">Questions</span>
        </h1>
        <p class="text-sm text-gray-600 mb-6 font-medium max-w-xl mx-auto">
            Everything you need to know about rates, courier partners, COD remittance, NDR, and store integrations.
        </p>

        <!-- Search Bar -->
        <div class="relative max-w-lg mx-auto mb-6">
            <input type="text" x-model="searchQuery" placeholder="Search for questions (e.g. COD, rates, NDR)..." class="w-full px-4 py-2.5 pl-10 rounded-full border border-gray-200 shadow-sm text-xs focus:outline-none focus:border-brand-navy bg-white">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
        </div>

        <!-- Category Filter Pills -->
        <div class="flex flex-wrap justify-center gap-1.5 mb-8 text-[11px] font-bold">
            <button @click="activeCategory = 'all'" :class="activeCategory === 'all' ? 'bg-brand-navy text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'" class="px-3 py-1.5 rounded-full transition">All FAQs</button>
            @foreach($faqs->pluck('category')->unique()->filter() as $cat)
            <button @click="activeCategory = '{{ $cat }}'" :class="activeCategory === '{{ $cat }}' ? 'bg-brand-navy text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'" class="px-3 py-1.5 rounded-full transition capitalize">{{ ucfirst(str_replace('_', ' ', $cat)) }}</button>
            @endforeach
        </div>
    </div>

    <!-- Accordion FAQ List - powered by DB data via Alpine -->
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($faqs->isEmpty())
        <div class="text-center text-gray-400 py-12">
            <i class="fa-solid fa-circle-question text-4xl mb-4"></i>
            <p class="font-bold">No FAQs available yet.</p>
            <p class="text-xs mt-1">Check back soon or contact our support team.</p>
        </div>
        @else
        <div class="space-y-3">
            <template x-for="(faq, index) in filteredFaqs()" :key="index">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden transition">
                    <button @click="openFaq = (openFaq === index ? null : index)" class="w-full text-left p-4 flex justify-between items-center font-bold text-gray-900 text-sm focus:outline-none">
                        <span x-text="faq.q"></span>
                        <i class="fa-solid text-xs ml-3" :class="openFaq === index ? 'fa-chevron-up text-brand-red' : 'fa-chevron-down text-gray-400'"></i>
                    </button>
                    <div x-show="openFaq === index" class="px-4 pb-4 text-xs text-gray-600 border-t border-gray-100 pt-2.5 leading-relaxed">
                        <p x-text="faq.a"></p>
                    </div>
                </div>
            </template>
            <div x-show="filteredFaqs().length === 0" class="text-center py-8 text-gray-400 text-sm">
                No FAQs match your search. <a href="{{ route('contact') }}" class="text-brand-navy font-bold underline">Ask us directly</a>.
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Bottom Minimalist CTA -->
<section class="py-8 bg-white border-t border-gray-100 text-center">
    <div class="max-w-xl mx-auto px-4">
        <h3 class="text-lg font-bold text-brand-navy mb-1">Still Have Questions?</h3>
        <p class="text-xs text-gray-500 mb-4">Our support team is available 24/7 to assist you.</p>
        <div class="flex justify-center gap-3">
            <a href="{{ route('contact') }}" class="px-5 py-2 rounded-full bg-brand-navy text-white font-bold text-xs hover:bg-brand-blue transition">
                Contact Support
            </a>
        </div>
    </div>
</section>

<script>
function faqApp() {
    return {
        searchQuery: '',
        activeCategory: 'all',
        openFaq: null,
        // DB-sourced FAQs injected from Blade
        faqs: @json($faqs->map(fn($f) => ['q' => $f->question, 'a' => $f->answer, 'category' => $f->category ?? 'general'])),
        filteredFaqs() {
            return this.faqs.filter(faq => {
                const matchesCategory = (this.activeCategory === 'all' || faq.category === this.activeCategory);
                const matchesSearch = faq.q.toLowerCase().includes(this.searchQuery.toLowerCase()) || faq.a.toLowerCase().includes(this.searchQuery.toLowerCase());
                return matchesCategory && matchesSearch;
            });
        }
    };
}
</script>
@endsection

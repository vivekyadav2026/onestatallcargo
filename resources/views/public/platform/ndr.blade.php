@extends('layouts.public')
@section('title', "Automated NDR Management | OneStall Cargo Platform")

@section('content')
<!-- 1. Hero Section -->
<section class="bg-gradient-to-r from-blue-50/40 via-white to-blue-50/40 py-12 md:py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="w-full lg:w-1/2 text-center lg:text-left">
                <div class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-brand-navy text-xs font-bold mb-4 uppercase tracking-wide">
                    Non-Delivery Report Engine
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold text-brand-navy leading-tight mb-4">
                    Reduce RTO Losses by <span class="text-brand-red">Up to 40%</span> with Automated NDR
                </h1>
                <p class="text-base md:text-lg text-gray-700 mb-8 font-medium leading-relaxed">
                    Convert failed delivery attempts into successful handovers. Re-engage buyers instantly via automated IVR calls, WhatsApp, and SMS workflows to capture updated address details.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md shadow-red-500/20">
                        Start Automated NDR
                    </a>
                    <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border-2 border-brand-navy text-brand-navy font-bold text-base hover:bg-brand-navy hover:text-white transition">
                        Talk to NDR Specialist
                    </a>
                </div>
            </div>

            <div class="w-full lg:w-1/2 relative">
                <div class="relative rounded-2xl overflow-hidden shadow-xl border-4 border-white bg-white">
                    <img src="{{ asset('images/dashboard.jpg') }}" alt="NDR Management Dashboard" class="w-full h-80 md:h-96 object-cover">
                </div>
                <div class="absolute -bottom-4 -left-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold">
                        <i class="fa-solid fa-phone-volume"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Buyer Outreach</div>
                        <div class="text-sm font-bold text-brand-navy">IVR, SMS & WhatsApp</div>
                    </div>
                </div>
                <div class="absolute -top-4 -right-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-brand-navy font-bold">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">NDR Conversion</div>
                        <div class="text-sm font-bold text-brand-red">40% Recovered Orders</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Metrics Bar -->
<section class="bg-brand-navy py-6 text-white border-y border-brand-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">40%</div>
                <div class="text-xs md:text-sm text-gray-300">RTO Reduction</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">&lt; 15 Mins</div>
                <div class="text-xs md:text-sm text-gray-300">NDR Signal Response Time</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-brand-red">Multi-Channel</div>
                <div class="text-xs md:text-sm text-gray-300">IVR, SMS & WhatsApp</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">Real-Time</div>
                <div class="text-xs md:text-sm text-gray-300">Carrier Re-attempt API</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Key Capabilities Grid -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-4xl font-extrabold text-brand-navy mb-3">
                Automated NDR Resolution Features
            </h2>
            <p class="text-base text-gray-600">
                Stop manual phone calls. Automate buyer verification and re-dispatch instructions instantly.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-brand-navy flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-headset text-brand-navy"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Automated Buyer IVR Calls</h3>
                <p class="text-sm text-gray-600">Instant outbound IVR phone calls placed to buyers on non-delivery to confirm preferred delivery date and time slot.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-red-100 text-brand-red flex items-center justify-center text-2xl mb-4">
                    <i class="fa-brands fa-whatsapp text-brand-red"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Interactive WhatsApp NDR Bot</h3>
                <p class="text-sm text-gray-600">Send WhatsApp messages with interactive buttons allowing buyers to share updated address details or change delivery dates.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-[#E7004C] fa-shield-triangle-exclamation"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Courier Fake Remark Detection</h3>
                <p class="text-sm text-gray-600">Cross-verify rider remarks ("Customer Refused" / "Door Closed") via automated buyer SMS response to catch fake courier attempts.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Direct Carrier Re-attempt API</h3>
                <p class="text-sm text-gray-600">Once buyer updates details, OneStall automatically pushes re-attempt requests directly to courier manifest systems.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-location-pen"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Buyer Self-Service Address Portal</h3>
                <p class="text-sm text-gray-600">A web link sent to buyers allowing them to correct pin codes, add nearby landmarks, or update phone numbers.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-chart-column"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">NDR Analytics Dashboard</h3>
                <p class="text-sm text-gray-600">Analyze courier-wise NDR conversion performance, top undelivered reasons, and agent resolution metrics.</p>
            </div>
        </div>
    </div>
</section>

<!-- 4. Workflow Section -->
<section class="py-12 md:py-16 bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-3xl font-extrabold text-brand-navy mb-2">
                4 Steps to Recover Undelivered Orders
            </h2>
            <p class="text-sm md:text-base text-gray-600 font-medium">Automated NDR recovery workflow</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">1</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">NDR Triggered</h4>
                <p class="text-xs text-gray-600">Courier flags failed delivery attempt in real-time.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">2</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Automated Outreach</h4>
                <p class="text-xs text-gray-600">System places IVR call & WhatsApp message to buyer.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">3</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Buyer Updates Detail</h4>
                <p class="text-xs text-gray-600">Buyer selects new date or provides updated landmark.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">4</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Re-attempt & Delivered</h4>
                <p class="text-xs text-gray-600">Courier re-delivers package successfully to buyer.</p>
            </div>
        </div>
    </div>
</section>

<!-- 5. CTA Card -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-brand-navy via-brand-blue to-brand-navy rounded-3xl p-8 md:p-12 text-white text-center shadow-xl">
            <h2 class="text-2xl md:text-4xl font-extrabold mb-4">
                Stop Losing Orders to NDR & RTO Today
            </h2>
            <p class="text-sm md:text-base text-gray-200 max-w-2xl mx-auto mb-8 font-medium">
                Automate your non-delivery report workflow and convert up to 40% of undelivered packages into successful sales.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md">
                    Start Automated NDR Free
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border border-white/40 text-white font-bold text-base hover:bg-white/10 transition">
                    Speak to Logistics Specialist
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
@extends('layouts.public')
@section('title', "AWB & Thermal Label Printing | OneStall Cargo Platform")

@section('content')
<!-- 1. Hero Section -->
<section class="bg-gradient-to-r from-blue-50/40 via-white to-blue-50/40 py-12 md:py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="w-full lg:w-1/2 text-center lg:text-left">
                <div class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-brand-navy text-xs font-bold mb-4 uppercase tracking-wide">
                    Fulfillment Infrastructure
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold text-brand-navy leading-tight mb-4">
                    Automated <span class="text-brand-red">Bulk AWB & Label</span> Generation
                </h1>
                <p class="text-base md:text-lg text-gray-700 mb-8 font-medium leading-relaxed">
                    Print thousands of standardized 4x6 thermal shipping labels, packing slips, tax invoices, and carrier pickup manifests in a single click.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md shadow-red-500/20">
                        Generate Labels Free
                    </a>
                    <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border-2 border-brand-navy text-brand-navy font-bold text-base hover:bg-brand-navy hover:text-white transition">
                        Explore Fulfillment Tech
                    </a>
                </div>
            </div>

            <div class="w-full lg:w-1/2 relative">
                <div class="relative rounded-2xl overflow-hidden shadow-xl border-4 border-white bg-white">
                    <img src="{{ asset('images/dashboard.jpg') }}" alt="AWB & Label Printing Dashboard" class="w-full h-80 md:h-96 object-cover">
                </div>
                <div class="absolute -bottom-4 -left-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold">
                        <i class="fa-solid fa-barcode"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Batch Processing</div>
                        <div class="text-sm font-bold text-brand-navy">1,000+ Labels / Min</div>
                    </div>
                </div>
                <div class="absolute -top-4 -right-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-brand-navy font-bold">
                        <i class="fa-solid fa-print"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Printer Support</div>
                        <div class="text-sm font-bold text-brand-red">Standard 4x6 Thermal</div>
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
                <div class="text-2xl md:text-3xl font-black text-white">1,000+</div>
                <div class="text-xs md:text-sm text-gray-300">Labels Batch Processing</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">4x6 Inch</div>
                <div class="text-xs md:text-sm text-gray-300">Standard Thermal Print</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-brand-red">100%</div>
                <div class="text-xs md:text-sm text-gray-300">Hub Scan Efficiency</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">Unified</div>
                <div class="text-xs md:text-sm text-gray-300">Multi-Carrier Format</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Key Capabilities Grid -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-4xl font-extrabold text-brand-navy mb-3">
                Automated Warehouse Labeling & Documentation
            </h2>
            <p class="text-base text-gray-600">
                Speed up warehouse packing and eliminate dispatch errors with standardized thermal printing tools.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-brand-navy flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-barcode text-brand-navy"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Instant Bulk AWB Allocation</h3>
                <p class="text-sm text-gray-600">Assign master tracking numbers to thousands of orders simultaneously across all your connected courier accounts.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-red-100 text-brand-red flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-print text-brand-red"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">4x6 Thermal Printer Compatible</h3>
                <p class="text-sm text-gray-600">Pre-configured for Zebra, TSC, TVS, and Xprinter thermal label printers for crisp, smudge-free barcode printing.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-[#E7004C] fa-file-lines text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Combo Invoice-Cum-Label</h3>
                <p class="text-sm text-gray-600">Print shipping labels integrated with product packing lists to ensure warehouse pickers put the right items in every box.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-clipboard-check text-purple-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Carrier Pickup Manifests</h3>
                <p class="text-sm text-gray-600">Generate digital & printable handover manifests detailing parcel counts for driver sign-off upon pickup.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-layer-group text-orange-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Unified Label Format</h3>
                <p class="text-sm text-gray-600">Standardized 1-page layout whether shipping via Delhivery, BlueDart, or Shadowfax to simplify packing operations.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-qrcode text-teal-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">High-Density Barcode Encoding</h3>
                <p class="text-sm text-gray-600">High-resolution 1D & 2D barcodes ensuring 100% scan efficiency at automated sorting hubs.</p>
            </div>
        </div>
    </div>
</section>

<!-- 4. Workflow Section -->
<section class="py-12 md:py-16 bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-3xl font-extrabold text-brand-navy mb-2">
                4 Steps to Fast Fulfillment
            </h2>
            <p class="text-sm md:text-base text-gray-600 font-medium">Seamless warehouse label & manifest workflow</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">1</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Select Orders</h4>
                <p class="text-xs text-gray-600">Select unfulfilled orders in your dashboard.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">2</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">1-Click AWB & Print</h4>
                <p class="text-xs text-gray-600">Generate AWBs & batch print 4x6 thermal labels.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">3</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Pack & Label</h4>
                <p class="text-xs text-gray-600">Affix thermal labels onto packages.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">4</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Manifest Sign-Off</h4>
                <p class="text-xs text-gray-600">Hand over parcels to courier driver with manifest sign-off.</p>
            </div>
        </div>
    </div>
</section>

<!-- 5. CTA Card -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-brand-navy via-brand-blue to-brand-navy rounded-3xl p-8 md:p-12 text-white text-center shadow-xl">
            <h2 class="text-2xl md:text-4xl font-extrabold mb-4">
                Automate Your Warehouse Fulfillment Today
            </h2>
            <p class="text-sm md:text-base text-gray-200 max-w-2xl mx-auto mb-8 font-medium">
                Speed up order packing, eliminate labeling errors, and generate batch thermal labels in seconds.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md">
                    Generate Bulk Labels Free
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border border-white/40 text-white font-bold text-base hover:bg-white/10 transition">
                    Contact Operations Team
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
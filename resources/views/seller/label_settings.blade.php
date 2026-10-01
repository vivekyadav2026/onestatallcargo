@extends('layouts.seller')
@section('title', 'Label Setting - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <div class="flex items-center justify-between">
        <h1 class="text-xl md:text-2xl font-bold text-gray-900">Label Setting</h1>
        <div class="text-sm font-semibold text-gray-500">
            <span>Dashboard</span> / <span class="text-gray-900">Label Setting</span>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 font-bold rounded-xl border border-green-200 text-sm">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('seller.label-settings.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8 space-y-10">
            
            <!-- Label Type -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Thermal -->
                <label class="cursor-pointer group">
                    <div class="flex items-center gap-3 mb-4 justify-center">
                        <input type="radio" name="label_type" value="thermal" class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500" {{ ($settings['label_type'] ?? 'thermal') === 'thermal' ? 'checked' : '' }}>
                        <span class="font-bold text-[#1e293b] text-base md:text-lg group-hover:text-blue-600 transition">Thermal Label</span>
                    </div>
                    <div class="border-2 rounded-xl overflow-hidden {{ ($settings['label_type'] ?? 'thermal') === 'thermal' ? 'border-blue-500' : 'border-transparent group-hover:border-blue-200' }} transition p-2">
                        <!-- Placeholder for thermal image (Using CSS for illustration) -->
                        <div class="w-full aspect-[3/4] bg-gray-50 border-2 border-gray-300 flex flex-col justify-between p-4 relative before:absolute before:top-[-10px] before:left-[-10px] before:right-[-10px] before:bottom-[-10px] before:border-t before:border-b before:border-gray-400">
                            <div class="border-b border-gray-300 pb-2 mb-2 flex justify-between">
                                <span class="font-bold text-xs">OneStall</span>
                                <span class="font-bold text-xs">P</span>
                            </div>
                            <div class="flex-1 flex flex-col gap-2">
                                <div class="h-2 bg-gray-200 rounded w-full"></div>
                                <div class="h-2 bg-gray-200 rounded w-3/4"></div>
                                <div class="h-2 bg-gray-200 rounded w-1/2"></div>
                            </div>
                            <div class="mt-4 pt-2 border-t border-gray-300 text-center">
                                <div class="h-6 bg-gray-800 rounded w-full mb-1"></div>
                                <span class="text-[8px] font-bold tracking-widest">PLEASE HANDLE WITH CARE</span>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Single Page -->
                <label class="cursor-pointer group">
                    <div class="flex items-center gap-3 mb-4 justify-center">
                        <input type="radio" name="label_type" value="single" class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500" {{ ($settings['label_type'] ?? '') === 'single' ? 'checked' : '' }}>
                        <span class="font-bold text-[#1e293b] text-base md:text-lg group-hover:text-blue-600 transition">Single Page Label</span>
                    </div>
                    <div class="border-2 rounded-xl overflow-hidden {{ ($settings['label_type'] ?? '') === 'single' ? 'border-blue-500' : 'border-transparent group-hover:border-blue-200' }} transition p-2">
                        <div class="w-full aspect-[3/4] bg-white border border-gray-300 p-4 relative before:absolute before:top-[-10px] before:left-[-10px] before:right-[-10px] before:bottom-[-10px] before:border-t before:border-b before:border-gray-400 flex flex-col justify-start">
                            <div class="border-b-2 border-gray-800 pb-2 mb-2 w-3/4 self-center text-center font-bold text-[8px]">
                                OneStall Cargo Label
                            </div>
                            <div class="flex gap-2">
                                <div class="flex-1 flex flex-col gap-1">
                                    <div class="h-1 bg-gray-200 rounded w-full"></div>
                                    <div class="h-1 bg-gray-200 rounded w-3/4"></div>
                                </div>
                                <div class="flex-1 flex flex-col gap-1 border-l border-gray-200 pl-2">
                                    <div class="h-1 bg-gray-200 rounded w-full"></div>
                                </div>
                            </div>
                            <div class="mt-auto border-t-2 border-gray-800 pt-2 text-center">
                                <div class="h-4 bg-gray-800 rounded w-full mb-1"></div>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Multiple Page -->
                <label class="cursor-pointer group">
                    <div class="flex items-center gap-3 mb-4 justify-center">
                        <input type="radio" name="label_type" value="multiple" class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500" {{ ($settings['label_type'] ?? '') === 'multiple' ? 'checked' : '' }}>
                        <span class="font-bold text-[#1e293b] text-base md:text-lg group-hover:text-blue-600 transition">Multiple Page Label</span>
                    </div>
                    <div class="border-2 rounded-xl overflow-hidden {{ ($settings['label_type'] ?? '') === 'multiple' ? 'border-blue-500' : 'border-transparent group-hover:border-blue-200' }} transition p-2">
                        <div class="w-full aspect-[3/4] bg-white border border-gray-300 p-2 relative before:absolute before:top-[-10px] before:left-[-10px] before:right-[-10px] before:bottom-[-10px] before:border-t before:border-b before:border-gray-400 grid grid-cols-2 gap-2">
                            <!-- Label 1 -->
                            <div class="border border-gray-400 p-1 flex flex-col justify-between">
                                <div class="h-1 bg-gray-200 mb-1 w-1/2"></div>
                                <div class="mt-auto"><div class="h-2 bg-gray-800 w-full"></div></div>
                            </div>
                            <!-- Label 2 -->
                            <div class="border border-gray-400 p-1 flex flex-col justify-between">
                                <div class="h-1 bg-gray-200 mb-1 w-1/2"></div>
                                <div class="mt-auto"><div class="h-2 bg-gray-800 w-full"></div></div>
                            </div>
                            <!-- Label 3 -->
                            <div class="border border-gray-400 p-1 flex flex-col justify-between">
                                <div class="h-1 bg-gray-200 mb-1 w-1/2"></div>
                                <div class="mt-auto"><div class="h-2 bg-gray-800 w-full"></div></div>
                            </div>
                            <!-- Label 4 -->
                            <div class="border border-gray-400 p-1 flex flex-col justify-between">
                                <div class="h-1 bg-gray-200 mb-1 w-1/2"></div>
                                <div class="mt-auto"><div class="h-2 bg-gray-800 w-full"></div></div>
                            </div>
                        </div>
                    </div>
                </label>
            </div>

            <hr class="border-gray-200">

            <!-- Details to show on label -->
            <div>
                <h3 class="text-lg font-extrabold text-gray-900 mb-6">Details to show on label</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <!-- Box 1 -->
                    <div class="border border-gray-200 rounded-2xl p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-gray-900 text-sm">Enable Product Name</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="enable_product_name" value="1" class="sr-only peer" {{ !empty($settings['enable_product_name']) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200 text-xs font-semibold text-yellow-800">
                            <span class="font-extrabold">Note —</span> For BlueDart courier, product details will be visible even if this setting is disabled.
                        </div>
                    </div>

                    <!-- Box 2 -->
                    <div class="border border-gray-200 rounded-2xl p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-gray-900 text-sm">Enable Consignee Contact Number</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="enable_consignee_contact" value="1" class="sr-only peer" {{ !empty($settings['enable_consignee_contact']) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200 text-xs font-semibold text-yellow-800">
                            <span class="font-extrabold">Note —</span> For TCI courier, consignee contact number will be visible even if this setting is disabled.
                        </div>
                    </div>

                    <!-- Box 3 -->
                    <div class="border border-gray-200 rounded-2xl p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-gray-900 text-sm">Enable Consignee Address</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="enable_consignee_address" value="1" class="sr-only peer" {{ !empty($settings['enable_consignee_address']) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200 text-xs font-semibold text-yellow-800">
                            <span class="font-extrabold">Note —</span> For TCI courier, the Consignee Address will be visible even if this setting is disabled.
                        </div>
                    </div>

                    <!-- Box 4 -->
                    <div class="border border-gray-200 rounded-2xl p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-gray-900 text-sm">Enable Support Contact Number</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="enable_support_contact" value="1" class="sr-only peer" {{ !empty($settings['enable_support_contact']) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200 text-xs font-semibold text-yellow-800">
                            <span class="font-extrabold">Note —</span> For all LTL couriers and TCI, BlueDart — support contact numbers are not provided.
                        </div>
                    </div>

                    <!-- Box 5 -->
                    <div class="border border-gray-200 rounded-2xl p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-gray-900 text-sm">Enable Support Email</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="enable_support_email" value="1" class="sr-only peer" {{ !empty($settings['enable_support_email']) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200 text-xs font-semibold text-yellow-800">
                            <span class="font-extrabold">Note —</span> For all LTL couriers and TCI, BlueDart — support Email ID is not provided.
                        </div>
                    </div>

                    <!-- Box 6 -->
                    <div class="border border-gray-200 rounded-2xl p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-gray-900 text-sm">Enable RTO Address</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="enable_rto_address" value="1" class="sr-only peer" {{ !empty($settings['enable_rto_address']) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200 text-xs font-semibold text-yellow-800">
                            <span class="font-extrabold">Note —</span> For BlueDart and TCI, the consignor RTO Address will be visible even if this setting is disabled.
                        </div>
                    </div>

                </div>

                <!-- Notes Section -->
                <div class="mt-8 space-y-1">
                    <h4 class="font-extrabold text-gray-900 text-sm">Note:</h4>
                    <p class="text-red-500 italic text-sm">1. Not applicable for Amazon, MOVIN and Gati label</p>
                    <p class="text-red-500 italic text-sm">2. Label settings will not be reflected for the orders whose Labels have already been generated</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-4">
                <button type="reset" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg transition shadow-sm text-sm">
                    Reset
                </button>
                <button type="submit" class="px-6 py-2.5 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-lg transition shadow-sm text-sm">
                    Save Settings
                </button>
            </div>
            
        </div>
    </form>
</div>
@endsection

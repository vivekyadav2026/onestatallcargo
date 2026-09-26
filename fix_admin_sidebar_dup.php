<?php
$file = __DIR__ . '/resources/views/layouts/admin.blade.php';
$content = file_get_contents($file);

$search = <<<HTML
                        <div class="px-2 mt-6 mb-2"><div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Content Management</div></div>
                        <a href="{{ route('admin.banners.index') }}" class="sidebar-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}"><i class="fa-solid fa-image w-4 text-center"></i> <span>Promo Banners</span></a>
                        <a href="{{ route('admin.services.index') }}" class="sidebar-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}"><i class="fa-solid fa-list-check w-4 text-center"></i> <span>Services</span></a>
                        <a href="{{ route('admin.faqs.index') }}" class="sidebar-item {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}"><i class="fa-solid fa-circle-question w-4 text-center"></i> <span>FAQs</span></a>
                        <a href="{{ route('admin.testimonials.index') }}" class="sidebar-item {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}"><i class="fa-solid fa-star w-4 text-center"></i> <span>Testimonials</span></a>
HTML;

// Only replace the FIRST occurrence so we are left with just one!
$pos = strpos($content, $search);
if ($pos !== false) {
    $content = substr_replace($content, '', $pos, strlen($search));
    file_put_contents($file, $content);
    echo "Removed duplicated block.\n";
} else {
    echo "Block not found exactly. We'll use regex.\n";
}

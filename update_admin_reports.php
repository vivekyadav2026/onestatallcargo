<?php
$file = __DIR__ . '/resources/views/admin/reports/index.blade.php';
$content = file_get_contents($file);

$statsHtml = <<<'HTML'
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm cursor-pointer hover:shadow-md transition">
            <i class="fa-solid fa-calendar-day text-3xl text-blue-500 mb-4"></i>
            <h3 class="font-bold text-gray-900 text-lg">Daily Bookings</h3>
            <p class="text-3xl font-black text-gray-900 mt-2">{{ number_format($dailyBookings ?? 0) }}</p>
            <p class="text-[11px] font-bold text-gray-500 mt-1 uppercase tracking-wider">Shipments generated today</p>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm cursor-pointer hover:shadow-md transition">
            <i class="fa-solid fa-users text-3xl text-green-500 mb-4"></i>
            <h3 class="font-bold text-gray-900 text-lg">Top Seller Volume</h3>
            <p class="text-3xl font-black text-gray-900 mt-2">{{ number_format($topSeller->total ?? 0) }}</p>
            <p class="text-[11px] font-bold text-gray-500 mt-1 uppercase tracking-wider">By {{ $topSeller->name ?? 'N/A' }}</p>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm cursor-pointer hover:shadow-md transition">
            <i class="fa-solid fa-money-check-dollar text-3xl text-[var(--gold-deep)] mb-4"></i>
            <h3 class="font-bold text-gray-900 text-lg">Pending COD Remittances</h3>
            <p class="text-3xl font-black text-gray-900 mt-2">₹{{ number_format($pendingCod ?? 0, 2) }}</p>
            <p class="text-[11px] font-bold text-gray-500 mt-1 uppercase tracking-wider">Unsettled Delivered COD</p>
        </div>
    </div>
HTML;

$content = preg_replace('/<div class="grid grid-cols-1.*?<\/div>\s*<\/div>\s*<\/div>/is', ltrim($statsHtml), $content);
file_put_contents($file, $content);
echo "Added dynamic stats to reports dashboard.\n";

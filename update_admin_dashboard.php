<?php
$file = __DIR__ . '/resources/views/admin/dashboard.blade.php';
$content = file_get_contents($file);

$recentBookingsHtml = <<<'HTML'
            <!-- Recent Bookings Widget -->
            <div class="p-6 rounded-3xl bg-white border border-gray-200 shadow-sm space-y-4 mt-6">
                <div class="flex items-center justify-between border-b pb-4 border-gray-100">
                    <div>
                        <h3 class="font-extrabold text-base text-gray-900">Recent Bookings</h3>
                        <p class="text-xs text-gray-500 mt-1">Latest shipments processed across the platform</p>
                    </div>
                    <a href="{{ route('admin.shipments.index') }}" class="text-xs font-bold text-[var(--gold-deep)] hover:underline">View All Shipments</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 border-b border-gray-100">
                                <th class="pb-3 font-medium">AWB Number</th>
                                <th class="pb-3 font-medium">Seller / Client</th>
                                <th class="pb-3 font-medium">Status</th>
                                <th class="pb-3 font-medium text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-gray-600 font-medium">
                            @foreach($recentShipments ?? [] as $booking)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 font-bold text-[#4338ca]">{{ $booking['awb'] }}</td>
                                <td class="py-3">{{ $booking['seller'] }}</td>
                                <td class="py-3">
                                    @php
                                        $statusClass = match($booking['status']) {
                                            'Delivered' => 'bg-green-50 text-green-700',
                                            'In Transit' => 'bg-yellow-50 text-yellow-700',
                                            'NDR' => 'bg-red-50 text-red-700',
                                            'RTO Initiated', 'RTO Delivered' => 'bg-gray-100 text-gray-700',
                                            default => 'bg-blue-50 text-blue-700'
                                        };
                                    @endphp
                                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $statusClass }}">
                                        {{ $booking['status'] }}
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <button class="text-gray-400 hover:text-gray-900 transition"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                </td>
                            </tr>
                            @endforeach
                            @if(empty($recentShipments))
                            <tr><td colspan="4" class="py-4 text-center text-gray-400">No recent shipments</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
HTML;

$content = str_replace('</div>
        </div>

        <!-- Right Column: Weak Zones / High NDR -->', ltrim($recentBookingsHtml) . "\n\n        <!-- Right Column: Weak Zones / High NDR -->", $content);

file_put_contents($file, $content);
echo "Fixed Admin Dashboard Blade.\n";

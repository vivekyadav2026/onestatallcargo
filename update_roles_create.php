<?php
$file = __DIR__ . '/resources/views/admin/roles/create.blade.php';
$content = file_get_contents($file);

$permissionsHtml = <<<'HTML'
            <!-- Granular Permissions Matrix -->
            <div>
                <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4 uppercase tracking-wider">Granular Module Permissions</h3>
                <p class="text-xs text-gray-500 mb-4">Select which specific Admin Panel modules this user can access (only applicable if role is Operations/Admin).</p>
                
                @php
                    $availablePermissions = [
                        'view_dashboard' => 'View Dashboard & Stats',
                        'manage_shipments' => 'Manage Shipments & Bookings',
                        'manage_pickups' => 'Assign & Manage Pickups',
                        'manage_ndr' => 'Process NDR & RTO',
                        'manage_billing' => 'Billing & Wallet Recharge',
                        'manage_users' => 'Roles & Permissions',
                        'manage_kyc' => 'Approve/Reject KYC',
                        'manage_rates' => 'Configure Shipping Rates',
                        'manage_content' => 'Update Promo Banners & CMS',
                    ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($availablePermissions as $key => $label)
                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <input type="checkbox" name="permissions[]" value="{{ $key }}" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-xs font-bold text-gray-700">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
HTML;

$content = str_replace('</div>
        
        <div class="px-6', ltrim($permissionsHtml) . "\n\n        </div>\n        \n        <div class=\"px-6", $content);

file_put_contents($file, $content);
echo "Updated roles create blade.\n";

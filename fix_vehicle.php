<?php
// Fix AdminRiderController
$adminController = file_get_contents("app/Http/Controllers/AdminRiderController.php");
$adminController = preg_replace('/\'vehicle_type\'\s*=>\s*\$validated\[\'vehicle_type\'\]/', "'vehicle_type' => \$validated['vehicle_type'] ?? null", $adminController);
$adminController = preg_replace('/\'vehicle_number\'\s*=>\s*\$validated\[\'vehicle_number\'\]/', "'vehicle_number' => \$validated['vehicle_number'] ?? null", $adminController);
file_put_contents("app/Http/Controllers/AdminRiderController.php", $adminController);

// Fix HubRiderController
$hubController = file_get_contents("app/Http/Controllers/HubRiderController.php");
$hubController = preg_replace('/\'vehicle_type\'\s*=>\s*\$validated\[\'vehicle_type\'\]/', "'vehicle_type' => \$validated['vehicle_type'] ?? null", $hubController);
$hubController = preg_replace('/\'vehicle_number\'\s*=>\s*\$validated\[\'vehicle_number\'\]/', "'vehicle_number' => \$validated['vehicle_number'] ?? null", $hubController);
file_put_contents("app/Http/Controllers/HubRiderController.php", $hubController);

// Fix Blade Template for Admin
$bladeContent = file_get_contents("resources/views/admin/riders/index.blade.php");

// Fix Register Form
$registerTarget = '                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Vehicle Type</label>
                            <input type="text" name="vehicle_type" placeholder="Bike, Van" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[var(--gold)] outline-none">
                        </div>';
$registerReplacement = '                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Vehicle Type</label>
                            <input type="text" name="vehicle_type" placeholder="Bike, Van" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[var(--gold)] outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Vehicle Number</label>
                            <input type="text" name="vehicle_number" placeholder="DL-12-AB-3456" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[var(--gold)] outline-none">
                        </div>';
                        
// I should make sure the grid columns handle the extra input correctly.
// Let's replace the whole grid section for Register.
$bladeContent = preg_replace('/<div class="grid grid-cols-2 gap-3">\s*<div>\s*<label class="block text-xs font-bold text-gray-700 mb-1">Rider Type<\/label>.*?<\/div>\s*<div>\s*<label class="block text-xs font-bold text-gray-700 mb-1">Vehicle Type<\/label>.*?<\/div>\s*<\/div>/s', 
    '<div class="grid grid-cols-3 gap-3">
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Rider Type</label>
            <select name="role" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[var(--gold)] outline-none">
                <option value="rider">Hybrid (Standard)</option>
                <option value="pickup_rider">Pickup Only</option>
                <option value="delivery_rider">Delivery Only</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Vehicle Type</label>
            <input type="text" name="vehicle_type" placeholder="Bike, Van" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[var(--gold)] outline-none">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Vehicle Number</label>
            <input type="text" name="vehicle_number" placeholder="DL-12-AB-3456" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[var(--gold)] outline-none">
        </div>
    </div>', $bladeContent);

// Fix Edit Form
$bladeContent = preg_replace('/<div class="grid grid-cols-2 gap-4">\s*<div>\s*<label class="block text-xs font-bold text-gray-700 mb-1">Vehicle Type<\/label>.*?<\/div>\s*<div>\s*<label class="block text-xs font-bold text-gray-700 mb-1">Assign Hub<\/label>.*?<\/div>\s*<\/div>/s', 
    '<div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Vehicle Type</label>
            <input type="text" name="vehicle_type" value="{{ $rider->vehicle_type }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Vehicle Number</label>
            <input type="text" name="vehicle_number" value="{{ $rider->vehicle_number }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Assign Hub</label>
            <select name="hub_id" class="w-full px-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[var(--gold)]">
                <option value="">Global / Unassigned</option>
                @foreach($hubs as $hub)
                    <option value="{{ $hub->id }}" {{ $rider->hub_id == $hub->id ? \'selected\' : \'\' }}>{{ $hub->name }}</option>
                @endforeach
            </select>
        </div>
    </div>', $bladeContent);

file_put_contents("resources/views/admin/riders/index.blade.php", $bladeContent);

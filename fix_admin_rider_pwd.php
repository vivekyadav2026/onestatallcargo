<?php
$content = file_get_contents("app/Http/Controllers/AdminRiderController.php");

$target = '        $validated = $request->validate([
            \'name\' => \'required|string|max:255\',
            \'phone\' => [\'required\', \'string\', Rule::unique(\'users\')->ignore($rider->user_id)],
            \'franchise_id\' => \'nullable|exists:franchises,id\',
            \'hub_id\' => \'nullable|exists:hubs,id\',
            \'vehicle_type\' => \'nullable|string\',
            \'vehicle_number\' => \'nullable|string\',
            \'status\' => \'required|in:active,suspended\',
        ]);';
        
$replacement = '        $validated = $request->validate([
            \'name\' => \'required|string|max:255\',
            \'phone\' => [\'required\', \'string\', Rule::unique(\'users\')->ignore($rider->user_id)],
            \'password\' => \'nullable|string|min:6\',
            \'franchise_id\' => \'nullable|exists:franchises,id\',
            \'hub_id\' => \'nullable|exists:hubs,id\',
            \'vehicle_type\' => \'nullable|string\',
            \'vehicle_number\' => \'nullable|string\',
            \'status\' => \'required|in:active,suspended\',
        ]);';

$content = str_replace($target, $replacement, $content);

$targetUpdate = '        $rider->user->update([
            \'name\' => $validated[\'name\'],
            \'phone\' => $validated[\'phone\']
        ]);';

$replacementUpdate = '        $userData = [
            \'name\' => $validated[\'name\'],
            \'phone\' => $validated[\'phone\']
        ];
        
        if (!empty($validated[\'password\'])) {
            $userData[\'password\'] = Hash::make($validated[\'password\']);
        }
        
        $rider->user->update($userData);';

$content = str_replace($targetUpdate, $replacementUpdate, $content);

file_put_contents("app/Http/Controllers/AdminRiderController.php", $content);

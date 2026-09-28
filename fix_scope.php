<?php
$controllers = [
    'app/Http/Controllers/HubRiderController.php',
    'app/Http/Controllers/HubAssignmentController.php',
    'app/Http/Controllers/HubNdrController.php',
    'app/Http/Controllers/HubWalletController.php'
];

foreach ($controllers as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Replace strict franchise block
    $content = preg_replace('/if \(!\$franchise\) \{\s*abort\(403, \'[^\']+\'\);\s*\}/s', '
        $isHubManager = \App\Models\Hub::where("manager_id", $user->id)->exists();
        if (!$franchise && !$isHubManager) {
            abort(403, "You are not a Franchise owner or Hub Manager.");
        }
    ', $content);
    
    // Adjust scope array mapping
    $content = preg_replace('/\'franchise_id\' => \$franchise->id,/', "'franchise_id' => \$franchise ? \$franchise->id : null,", $content);
    $content = preg_replace('/\'city\' => \$franchise->city/', "'city' => \$franchise ? \$franchise->city : 'Hub',", $content);
    
    // In index/update/store methods where it checks strictly against $scope['franchise_id']:
    // If franchise_id is null, it should check hub_id.
    
    file_put_contents($file, $content);
}

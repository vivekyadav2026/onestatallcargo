<?php
$content = file_get_contents("app/Http/Controllers/HubDashboardController.php");

$profileMethods = '
    public function profile()
    {
        $user = Auth::user();
        $franchise = \App\Models\Franchise::where(\'user_id\', $user->id)->first();
        $hub = \App\Models\Hub::where(\'manager_id\', $user->id)->first();
        
        return view(\'hub.profile\', compact(\'user\', \'franchise\', \'hub\'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            \'name\' => \'required|string|max:255\',
            \'phone\' => \'required|string|max:20\',
            \'password\' => \'nullable|string|min:6\',
        ]);

        $userData = [
            \'name\' => $validated[\'name\'],
            \'phone\' => $validated[\'phone\'],
        ];

        if (!empty($validated[\'password\'])) {
            $userData[\'password\'] = \Illuminate\Support\Facades\Hash::make($validated[\'password\']);
        }

        $user->update($userData);

        return back()->with(\'success\', \'Profile updated successfully.\');
    }
}';

$content = preg_replace('/}\s*$/', $profileMethods, $content);
file_put_contents("app/Http/Controllers/HubDashboardController.php", $content);

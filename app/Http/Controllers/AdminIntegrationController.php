<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class AdminIntegrationController extends Controller
{
    public function index()
    {
        $settings = Cache::rememberForever('global_settings', function () {
            return Setting::pluck('value', 'key')->toArray();
        });

        return view('admin.integrations', compact('settings'));
    }

    public function save(Request $request)
    {
        $data = $request->except(['_token']);
        
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Integration settings updated and saved securely.');
    }
}

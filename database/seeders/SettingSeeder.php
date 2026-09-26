<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'OneStall Cargo', 'group' => 'general'],
            ['key' => 'site_email', 'value' => 'support@onestallcargo.com', 'group' => 'contact'],
            ['key' => 'site_phone', 'value' => '1800-123-4567', 'group' => 'contact'],
            ['key' => 'site_address', 'value' => '123 Logistics Park, Mumbai, India 400001', 'group' => 'contact'],
            ['key' => 'facebook_url', 'value' => '#', 'group' => 'social'],
            ['key' => 'twitter_url', 'value' => '#', 'group' => 'social'],
            ['key' => 'linkedin_url', 'value' => '#', 'group' => 'social'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}

<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder {
    public function run() {
        User::create(["email" => "admin@onestallcargo.com", "name" => "Super Admin", "password" => Hash::make("password123"), "role" => "admin", "phone" => "9999999991", "company_name" => "OneStall HQ"]);
        User::create(["email" => "seller@onestallcargo.com", "name" => "Demo Seller", "password" => Hash::make("password123"), "role" => "seller", "phone" => "9999999992", "company_name" => "Trendz Fashion"]);
        User::create(["email" => "hub@onestallcargo.com", "name" => "Delhi Central Hub", "password" => Hash::make("password123"), "role" => "franchise", "phone" => "9999999993", "company_name" => "OneStall Delhi"]);
        User::create(["email" => "rider@onestallcargo.com", "name" => "Rahul (Rider)", "password" => Hash::make("password123"), "role" => "rider", "phone" => "9999999994"]);
    }
}

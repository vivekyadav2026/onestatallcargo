<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('₹500 FREE Shipping Credits');
            $table->string('subtitle')->default('are sitting in your wallet. Make first recharge of ₹1,000 to unlock.');
            $table->string('coupon_code')->default('FIRST1000');
            $table->string('button_text')->default('Get My Free Credits');
            $table->string('button_link')->default('/seller/wallet');
            $table->string('bg_gradient')->default('from-[#1d4ed8] via-[#2563eb] to-[#3b82f6]');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('banners')->insert([
            'title' => '₹500 FREE Shipping Credits',
            'subtitle' => 'are sitting in your wallet. Make first recharge of ₹1,000 to unlock.',
            'coupon_code' => 'FIRST1000',
            'button_text' => 'Get My Free Credits',
            'button_link' => '/seller/wallet',
            'bg_gradient' => 'from-[#1d4ed8] via-[#2563eb] to-[#3b82f6]',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('brand_name')->nullable()->after('company_name');
            $table->string('gstin', 15)->nullable()->after('brand_name');
            $table->string('pan_number', 10)->nullable()->after('gstin');
            $table->string('business_type')->nullable()->after('pan_number');
            $table->text('company_address')->nullable()->after('business_type');
            $table->string('company_city')->nullable()->after('company_address');
            $table->string('company_state')->nullable()->after('company_city');
            $table->string('company_pincode', 6)->nullable()->after('company_state');
            $table->string('website')->nullable()->after('company_pincode');
            $table->string('support_phone')->nullable()->after('website');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'brand_name', 'gstin', 'pan_number', 'business_type',
                'company_address', 'company_city', 'company_state',
                'company_pincode', 'website', 'support_phone'
            ]);
        });
    }
};

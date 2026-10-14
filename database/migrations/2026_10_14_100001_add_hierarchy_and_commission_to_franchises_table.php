<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('franchises', function (Blueprint $table) {
            if (!Schema::hasColumn('franchises', 'franchise_level')) {
                $table->string('franchise_level', 20)->default('district')->after('status'); // 'state' or 'district'
            }
            if (!Schema::hasColumn('franchises', 'parent_franchise_id')) {
                $table->unsignedBigInteger('parent_franchise_id')->nullable()->after('franchise_level');
                $table->foreign('parent_franchise_id')->references('id')->on('franchises')->onDelete('set null');
            }
            if (!Schema::hasColumn('franchises', 'district')) {
                $table->string('district', 100)->nullable()->after('city');
            }
            // Delivery commission configuration for District Franchise
            if (!Schema::hasColumn('franchises', 'delivery_commission_type')) {
                $table->string('delivery_commission_type', 20)->default('flat')->after('serviceable_pincodes'); // 'flat', 'per_kg', 'percent'
            }
            if (!Schema::hasColumn('franchises', 'delivery_commission_rate')) {
                $table->decimal('delivery_commission_rate', 10, 2)->default(0.00)->after('delivery_commission_type');
            }
            // First-mile pickup commission configuration for District Franchise
            if (!Schema::hasColumn('franchises', 'pickup_commission_type')) {
                $table->string('pickup_commission_type', 20)->default('flat')->after('delivery_commission_rate');
            }
            if (!Schema::hasColumn('franchises', 'pickup_commission_rate')) {
                $table->decimal('pickup_commission_rate', 10, 2)->default(0.00)->after('pickup_commission_type');
            }
            // State Royalty configuration for State Franchise
            if (!Schema::hasColumn('franchises', 'state_royalty_type')) {
                $table->string('state_royalty_type', 20)->default('percent')->after('pickup_commission_rate'); // 'flat', 'per_kg', 'percent'
            }
            if (!Schema::hasColumn('franchises', 'state_royalty_rate')) {
                $table->decimal('state_royalty_rate', 10, 2)->default(0.00)->after('state_royalty_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('franchises', function (Blueprint $table) {
            if (Schema::hasColumn('franchises', 'parent_franchise_id')) {
                $table->dropForeign(['parent_franchise_id']);
                $table->dropColumn('parent_franchise_id');
            }
            $table->dropColumn([
                'franchise_level',
                'district',
                'delivery_commission_type',
                'delivery_commission_rate',
                'pickup_commission_type',
                'pickup_commission_rate',
                'state_royalty_type',
                'state_royalty_rate',
            ]);
        });
    }
};

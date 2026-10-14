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
        Schema::table('shipments', function (Blueprint $table) {
            if (!Schema::hasColumn('shipments', 'state_franchise_id')) {
                $table->unsignedBigInteger('state_franchise_id')->nullable()->after('franchise_id');
                $table->foreign('state_franchise_id')->references('id')->on('franchises')->onDelete('set null');
            }
            if (!Schema::hasColumn('shipments', 'pickup_franchise_id')) {
                $table->unsignedBigInteger('pickup_franchise_id')->nullable()->after('state_franchise_id');
                $table->foreign('pickup_franchise_id')->references('id')->on('franchises')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            if (Schema::hasColumn('shipments', 'state_franchise_id')) {
                $table->dropForeign(['state_franchise_id']);
                $table->dropColumn('state_franchise_id');
            }
            if (Schema::hasColumn('shipments', 'pickup_franchise_id')) {
                $table->dropForeign(['pickup_franchise_id']);
                $table->dropColumn('pickup_franchise_id');
            }
        });
    }
};

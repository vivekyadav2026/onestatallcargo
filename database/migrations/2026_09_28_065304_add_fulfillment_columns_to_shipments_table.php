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
            if (!Schema::hasColumn('shipments', 'fulfillment_type')) {
                $table->string('fulfillment_type')->nullable()->after('status');
            }
            if (!Schema::hasColumn('shipments', 'provider_id')) {
                $table->unsignedBigInteger('provider_id')->nullable()->after('franchise_id');
            }
            if (!Schema::hasColumn('shipments', 'external_shipment_id')) {
                $table->string('external_shipment_id')->nullable()->after('provider_id');
            }
            if (!Schema::hasColumn('shipments', 'is_overridden')) {
                $table->boolean('is_overridden')->default(false)->after('external_shipment_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn(['fulfillment_type', 'provider_id', 'external_shipment_id', 'is_overridden']);
        });
    }
};

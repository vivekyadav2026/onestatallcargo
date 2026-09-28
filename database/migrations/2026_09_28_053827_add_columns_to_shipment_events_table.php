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
        Schema::table('shipment_events', function (Blueprint $table) {
            if (!Schema::hasColumn('shipment_events', 'shipment_id')) {
                $table->unsignedBigInteger('shipment_id')->after('id');
            }
            if (!Schema::hasColumn('shipment_events', 'status')) {
                $table->string('status')->after('shipment_id');
            }
            if (!Schema::hasColumn('shipment_events', 'location')) {
                $table->string('location')->nullable()->after('status');
            }
            if (!Schema::hasColumn('shipment_events', 'remarks')) {
                $table->text('remarks')->nullable()->after('location');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment_events', function (Blueprint $table) {
            $table->dropColumn(['shipment_id', 'status', 'location', 'remarks']);
        });
    }
};

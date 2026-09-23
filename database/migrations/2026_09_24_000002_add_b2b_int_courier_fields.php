<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->string('shipment_type')->default('B2C'); // B2C, B2B, International
            $table->string('vehicle_type')->nullable(); // For B2B PTL/FTL
            $table->string('destination_country')->default('India'); // For International
            $table->decimal('customs_value', 10, 2)->nullable();
            $table->string('hs_code')->nullable();
            $table->string('courier_partner')->default('OneStall'); // Delhivery, BlueDart, etc.
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn(['shipment_type', 'vehicle_type', 'destination_country', 'customs_value', 'hs_code', 'courier_partner']);
        });
    }
};

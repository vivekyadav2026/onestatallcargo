<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->enum('cod_remittance_status', ['pending', 'processed', 'not_applicable'])->default('not_applicable')->after('status');
            $table->date('cod_remittance_date')->nullable()->after('cod_remittance_status');
            $table->decimal('early_cod_fee_deducted', 8, 2)->default(0.00)->after('cod_remittance_date');
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn(['cod_remittance_status', 'cod_remittance_date', 'early_cod_fee_deducted']);
        });
    }
};

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
        if (Schema::hasTable('rate_cards') && !Schema::hasColumn('rate_cards', 'cod_threshold_amount')) {
            Schema::table('rate_cards', function (Blueprint $table) {
                $table->decimal('cod_threshold_amount', 10, 2)->default(0.00)->after('cod_min_charge');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('rate_cards') && Schema::hasColumn('rate_cards', 'cod_threshold_amount')) {
            Schema::table('rate_cards', function (Blueprint $table) {
                $table->dropColumn('cod_threshold_amount');
            });
        }
    }
};

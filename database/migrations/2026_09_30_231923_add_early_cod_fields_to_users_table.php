<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('early_cod_plan')->default('standard')->after('status')->comment('standard, early_t1, early_t2, early_same_day');
            $table->decimal('early_cod_fee', 5, 2)->default(0.00)->after('early_cod_plan')->comment('Percentage fee for Early COD');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['early_cod_plan', 'early_cod_fee']);
        });
    }
};

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
        Schema::create('rate_cards', function (Blueprint $table) {
            $table->id();
            $table->string('version_name');
            $table->date('effective_from');
            $table->boolean('is_active')->default(false);
            $table->decimal('fsc_percent', 5, 2)->default(10.00);
            $table->decimal('cod_min_charge', 8, 2)->default(30.00);
            $table->decimal('cod_percent', 5, 2)->default(1.25);
            $table->decimal('dto_multiplier', 5, 2)->default(1.30);
            $table->decimal('qc_base_charge', 8, 2)->default(35.00);
            $table->integer('qc_included_params')->default(3);
            $table->decimal('qc_additional_param_charge', 8, 2)->default(5.00);
            $table->decimal('volumetric_divisor', 8, 2)->default(5000);
            $table->decimal('gst_percent', 5, 2)->default(18.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_cards');
    }
};

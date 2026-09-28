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
        Schema::create('rate_card_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rate_card_id')->constrained()->cascadeOnDelete();
            $table->string('zone_name'); // Zone 1, Zone 2
            $table->decimal('first_0_5_kg', 8, 2)->nullable();
            $table->decimal('addl_0_5_kg', 8, 2)->nullable();
            $table->decimal('first_2_kg', 8, 2)->nullable();
            $table->decimal('addl_1_kg_after_2', 8, 2)->nullable();
            $table->decimal('first_5_kg', 8, 2)->nullable();
            $table->decimal('addl_1_kg_after_5', 8, 2)->nullable();
            $table->decimal('first_10_kg', 8, 2)->nullable();
            $table->decimal('addl_1_kg_after_10', 8, 2)->nullable();
            $table->decimal('first_20_kg', 8, 2)->nullable();
            $table->decimal('addl_1_kg_after_20', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_card_zones');
    }
};

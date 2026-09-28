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
        Schema::create('serviceable_pincodes', function (Blueprint $table) {
            $table->id();
            $table->string('pincode')->unique();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->unsignedBigInteger('franchise_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serviceable_pincodes');
    }
};

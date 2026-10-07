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
        Schema::create('xpressbees_awbs', function (Blueprint $table) {
            $table->id();
            $table->string('awb_number')->unique();
            $table->enum('status', ['available', 'used'])->default('available');
            $table->string('order_id')->nullable();
            $table->enum('type', ['AWB', 'MPS'])->default('AWB');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xpressbees_awbs');
    }
};

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
        Schema::create('weight_discrepancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // The seller
            $table->decimal('applied_weight', 8, 3); // in kg
            $table->decimal('charged_weight', 8, 3); // in kg
            $table->decimal('discrepancy_fee', 10, 2); // amount to be deducted if accepted/won by courier
            $table->enum('status', ['pending', 'disputed', 'accepted', 'seller_won', 'courier_won'])->default('pending');
            $table->string('evidence_image')->nullable();
            $table->text('seller_remarks')->nullable();
            $table->text('admin_remarks')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weight_discrepancies');
    }
};

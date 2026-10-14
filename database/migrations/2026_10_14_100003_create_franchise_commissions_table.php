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
        Schema::create('franchise_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_id')->constrained('franchises')->onDelete('cascade');
            $table->foreignId('shipment_id')->constrained('shipments')->onDelete('cascade');
            $table->string('commission_type', 40); // 'district_delivery', 'district_pickup', 'state_royalty'
            $table->string('rate_type', 20)->default('flat'); // 'flat', 'per_kg', 'percent'
            $table->decimal('rate_applied', 10, 2)->default(0.00);
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->string('status', 20)->default('pending'); // 'pending', 'credited', 'reversed'
            $table->unsignedBigInteger('wallet_transaction_id')->nullable();
            $table->timestamp('credited_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Crucial idempotency constraint: a franchise cannot have duplicate commission records of the same type for a single shipment
            $table->unique(['franchise_id', 'shipment_id', 'commission_type'], 'uniq_franchise_shipment_comm');
            $table->index(['franchise_id', 'status']);
            $table->index(['shipment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franchise_commissions');
    }
};

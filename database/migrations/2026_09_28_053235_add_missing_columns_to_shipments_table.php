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
        Schema::table('shipments', function (Blueprint $table) {
            if (!Schema::hasColumn('shipments', 'order_id')) {
                $table->string('order_id')->nullable()->after('awb_number');
            }
            if (!Schema::hasColumn('shipments', 'franchise_id')) {
                $table->unsignedBigInteger('franchise_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('shipments', 'vendor_id')) {
                $table->unsignedBigInteger('vendor_id')->nullable()->after('franchise_id'); // For multi-vendor support
            }
            if (!Schema::hasColumn('shipments', 'shipping_charge')) {
                $table->decimal('shipping_charge', 10, 2)->nullable()->after('total_amount');
            }
            if (!Schema::hasColumn('shipments', 'courier_awb')) {
                $table->string('courier_awb')->nullable()->after('courier_partner');
            }
            if (!Schema::hasColumn('shipments', 'pickup_address')) {
                $table->text('pickup_address')->nullable()->after('receiver_phone');
                $table->string('pickup_city')->nullable()->after('pickup_address');
                $table->string('pickup_pincode')->nullable()->after('pickup_city');
            }
            if (!Schema::hasColumn('shipments', 'payment_type')) {
                $table->string('payment_type')->nullable()->after('is_cod');
            }
            
            // Add unique constraint to awb_number safely if it doesn't exist
            // SQLite might have trouble adding unique constraints dynamically, but let's try
            // $table->unique('awb_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn([
                'order_id', 'franchise_id', 'vendor_id', 'shipping_charge', 
                'courier_awb', 'pickup_address', 'pickup_city', 'pickup_pincode', 'payment_type'
            ]);
        });
    }
};

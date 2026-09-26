<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->text('product_details')->nullable()->after('invoice_value');
            $table->string('product_name')->nullable()->after('product_details');
            $table->string('product_sku')->nullable()->after('product_name');
            $table->integer('product_qty')->default(1)->after('product_sku');
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn(['product_details', 'product_name', 'product_sku', 'product_qty']);
        });
    }
};

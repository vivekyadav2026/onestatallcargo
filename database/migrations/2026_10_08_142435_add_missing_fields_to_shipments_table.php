<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->string('receiver_email')->nullable();
            $table->string('delivery_landmark')->nullable();
            $table->string('delivery_state')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn(['receiver_email', 'delivery_landmark', 'delivery_state']);
        });
    }
};

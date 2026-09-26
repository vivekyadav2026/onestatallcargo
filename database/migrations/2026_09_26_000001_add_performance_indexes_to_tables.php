<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'idx_ship_user_created');
            $table->index(['user_id', 'is_cod'], 'idx_ship_user_iscod');
            $table->index('awb_number', 'idx_ship_awb');
            $table->index('receiver_phone', 'idx_ship_phone');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'idx_usr_role');
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropIndex('idx_ship_user_created');
            $table->dropIndex('idx_ship_user_iscod');
            $table->dropIndex('idx_ship_awb');
            $table->dropIndex('idx_ship_phone');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_usr_role');
        });
    }
};

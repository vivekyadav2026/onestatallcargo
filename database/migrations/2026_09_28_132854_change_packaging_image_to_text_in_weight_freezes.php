<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weight_freezes', function (Blueprint $table) {
            $table->text('packaging_image')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('weight_freezes', function (Blueprint $table) {
            $table->string('packaging_image', 255)->nullable()->change();
        });
    }
};

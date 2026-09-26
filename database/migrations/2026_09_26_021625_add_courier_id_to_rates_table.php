<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rates', function (Blueprint $table) {
            if (!Schema::hasColumn('rates', 'courier_id')) {
                $table->unsignedBigInteger('courier_id')->nullable()->after('zone_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rates', function (Blueprint $table) {
            if (Schema::hasColumn('rates', 'courier_id')) {
                $table->dropColumn('courier_id');
            }
        });
    }
};

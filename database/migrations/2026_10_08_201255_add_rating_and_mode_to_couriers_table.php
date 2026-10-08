<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('couriers', function (Blueprint $table) {
            if (!Schema::hasColumn('couriers', 'rating')) {
                $table->decimal('rating', 2, 1)->default(4.0)->after('name');
            }
            if (!Schema::hasColumn('couriers', 'transport_mode')) {
                $table->string('transport_mode')->default('Surface')->after('rating');
            }
            if (!Schema::hasColumn('couriers', 'eta_days')) {
                $table->string('eta_days')->default('3-5')->after('transport_mode');
            }
        });
    }

    public function down(): void
    {
        Schema::table('couriers', function (Blueprint $table) {
            $table->dropColumn(['rating', 'transport_mode', 'eta_days']);
        });
    }
};
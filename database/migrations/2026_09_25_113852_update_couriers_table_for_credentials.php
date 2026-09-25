<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('couriers', function (Blueprint $table) {
            // Drop old api_code column if we want a clean break
            if (Schema::hasColumn('couriers', 'api_code')) {
                $table->dropColumn('api_code');
            }
            // Add robust credentials column
            if (!Schema::hasColumn('couriers', 'api_credentials')) {
                $table->text('api_credentials')->nullable()->after('name');
            }
            if (!Schema::hasColumn('couriers', 'mode')) {
                $table->string('mode')->default('sandbox')->after('name'); // sandbox or production
            }
        });
    }

    public function down(): void
    {
        Schema::table('couriers', function (Blueprint $table) {
            if (Schema::hasColumn('couriers', 'api_credentials')) {
                $table->dropColumn('api_credentials');
            }
            if (Schema::hasColumn('couriers', 'mode')) {
                $table->dropColumn('mode');
            }
            $table->string('api_code')->nullable();
        });
    }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('customer');
            $table->string('phone')->nullable();
            $table->string('company_name')->nullable();
            $table->decimal('wallet_balance', 10, 2)->default(0);
        });
    }
    public function down() {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'company_name', 'wallet_balance']);
        });
    }
};

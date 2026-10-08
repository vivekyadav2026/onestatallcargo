<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->decimal('cod_amount', 10, 2)->nullable()->after('invoice_value');
        });
    }

    public function down()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn('cod_amount');
        });
    }
};

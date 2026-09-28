<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('manifests', function (Blueprint $table) {
            $table->unique('bag_id');
        });
    }
    public function down() {
        Schema::table('manifests', function (Blueprint $table) {
            $table->dropUnique(['bag_id']);
        });
    }
};

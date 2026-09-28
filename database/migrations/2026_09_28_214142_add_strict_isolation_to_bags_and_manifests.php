<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('bags', function (Blueprint $table) {
            $table->unsignedBigInteger('franchise_id')->nullable()->after('hub_id');
            $table->unsignedBigInteger('destination_hub_id')->nullable()->after('destination');
            // Adding constraints
            $table->foreign('franchise_id')->references('id')->on('franchises')->onDelete('set null');
            $table->foreign('destination_hub_id')->references('id')->on('hubs')->onDelete('set null');
        });

        Schema::table('manifests', function (Blueprint $table) {
            $table->unsignedBigInteger('franchise_id')->nullable()->after('bag_id');
            $table->unsignedBigInteger('destination_hub_id')->nullable()->after('destination');
            $table->foreign('franchise_id')->references('id')->on('franchises')->onDelete('set null');
            $table->foreign('destination_hub_id')->references('id')->on('hubs')->onDelete('set null');
        });
    }

    public function down() {
        Schema::table('bags', function (Blueprint $table) {
            $table->dropForeign(['franchise_id']);
            $table->dropForeign(['destination_hub_id']);
            $table->dropColumn(['franchise_id', 'destination_hub_id']);
        });

        Schema::table('manifests', function (Blueprint $table) {
            $table->dropForeign(['franchise_id']);
            $table->dropForeign(['destination_hub_id']);
            $table->dropColumn(['franchise_id', 'destination_hub_id']);
        });
    }
};

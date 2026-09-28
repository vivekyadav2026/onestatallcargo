<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bags', function (Blueprint $table) {
            $table->string('destination')->nullable()->after('hub_id');
            $table->decimal('weight_kg', 8, 2)->nullable()->after('destination');
            $table->unsignedBigInteger('sealed_by')->nullable()->after('status');
            $table->timestamp('sealed_at')->nullable()->after('sealed_by');
            $table->timestamp('dispatched_at')->nullable()->after('sealed_at');
        });

        Schema::create('manifests', function (Blueprint $table) {
            $table->id();
            $table->string('manifest_number')->unique();
            $table->unsignedBigInteger('bag_id')->nullable();
            $table->unsignedBigInteger('source_hub_id')->nullable();
            $table->string('destination')->nullable();
            $table->integer('shipment_count')->default(0);
            $table->string('status')->default('CREATED');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamps();

            $table->foreign('bag_id')->references('id')->on('bags')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('manifests');

        Schema::table('bags', function (Blueprint $table) {
            $table->dropColumn(['destination', 'weight_kg', 'sealed_by', 'sealed_at', 'dispatched_at']);
        });
    }
};

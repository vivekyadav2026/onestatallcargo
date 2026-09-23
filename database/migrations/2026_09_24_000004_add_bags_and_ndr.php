<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bags', function (Blueprint $table) {
            $table->id();
            $table->string('bag_number')->unique();
            $table->unsignedBigInteger('hub_id')->nullable();
            $table->string('status')->default('Open'); // Open, Sealed, Dispatched, Received
            $table->timestamps();
        });

        Schema::table('shipments', function (Blueprint $table) {
            $table->string('ndr_reason')->nullable();
            $table->string('ndr_action')->nullable();
            $table->unsignedBigInteger('bag_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn(['ndr_reason', 'ndr_action', 'bag_id']);
        });
        Schema::dropIfExists('bags');
    }
};

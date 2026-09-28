<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('riders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->unsignedBigInteger('franchise_id')->nullable();
            $table->unsignedBigInteger('hub_id')->nullable();
            
            $table->string('vehicle_type')->nullable();
            $table->string('vehicle_number')->nullable();
            
            $table->string('status')->default('active'); // active, suspended, pending
            $table->boolean('is_active')->default(true); // Login operational enablement
            
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('franchise_id')->references('id')->on('franchises')->onDelete('set null');
            $table->foreign('hub_id')->references('id')->on('hubs')->onDelete('set null');
            
            $table->index('franchise_id');
            $table->index('hub_id');
            $table->index('status');
        });
    }

    public function down() {
        Schema::dropIfExists('riders');
    }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('contact_leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company_name')->nullable();
            $table->string('volume')->nullable();
            $table->text('message');
            $table->string('status')->default('New');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('contact_leads'); }
};


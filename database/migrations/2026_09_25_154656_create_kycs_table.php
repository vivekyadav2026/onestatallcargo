<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kycs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('business_type')->default('Individual');
            $table->string('document_type')->default('Aadhaar');
            $table->string('document_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('id_front_path')->nullable();
            $table->string('id_back_path')->nullable();
            $table->string('pan_doc_path')->nullable();
            $table->string('gst_doc_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kycs');
    }
};

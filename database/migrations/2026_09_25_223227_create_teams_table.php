<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create("teams", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("role");
            $table->string("image")->nullable();
            $table->string("linkedin_url")->nullable();
            $table->string("twitter_url")->nullable();
            $table->boolean("is_active")->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists("teams");
    }
};
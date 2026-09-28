<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_docs', function (Blueprint $table) {
            $table->id();
            $table->string('endpoint_key')->unique();
            $table->string('title');
            $table->string('method');
            $table->string('path');
            $table->text('description');
            $table->text('curl_example')->nullable();
            $table->text('json_response')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_docs');
    }
};

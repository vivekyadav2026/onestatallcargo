<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });
        
        Schema::table('mock_tests', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });
        
        Schema::table('certificates', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->after('user_id')->constrained()->cascadeOnDelete();
            $table->dropColumn('course_name');
        });
        
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->after('user_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');
        });
        
        Schema::table('mock_tests', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');
        });
        
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');
            $table->string('course_name')->default('MP Police Constable GD 2026');
        });
        
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');
        });
    }
};

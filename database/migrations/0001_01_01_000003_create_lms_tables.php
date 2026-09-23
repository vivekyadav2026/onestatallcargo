<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add role, pro status, and study goals to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('student')->after('email');
            }
            if (!Schema::hasColumn('users', 'is_pro')) {
                $table->boolean('is_pro')->default(false)->after('role');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('is_pro');
            }
            if (!Schema::hasColumn('users', 'goal')) {
                $table->string('goal')->default('MP Police GD 2026 में अंतिम चयन')->after('phone');
            }
            if (!Schema::hasColumn('users', 'daily_study_goal_minutes')) {
                $table->integer('daily_study_goal_minutes')->default(120)->after('goal');
            }
        });

        // Subjects Table
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name_hi');
            $table->string('name_en');
            $table->string('code')->unique();
            $table->integer('total_marks')->default(30);
            $table->integer('total_chapters')->default(10);
            $table->string('icon')->default('book-open');
            $table->integer('order')->default(1);
            $table->timestamps();
        });

        // Chapters Table
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->integer('chapter_number');
            $table->string('title_hi');
            $table->string('title_en');
            $table->text('description_hi')->nullable();
            $table->boolean('is_free_preview')->default(false);
            $table->integer('duration_minutes')->default(20);
            $table->integer('total_questions')->default(15);
            $table->longText('notes_content_hi')->nullable();
            $table->longText('notes_content_en')->nullable();
            $table->integer('order')->default(1);
            $table->timestamps();
        });

        // Mock Tests Table
        Schema::create('mock_tests', function (Blueprint $table) {
            $table->id();
            $table->integer('test_number');
            $table->string('title_hi');
            $table->string('title_en');
            $table->integer('duration_minutes')->default(120);
            $table->integer('total_questions')->default(100);
            $table->integer('total_marks')->default(100);
            $table->boolean('is_free')->default(false);
            $table->text('description_hi')->nullable();
            $table->timestamps();
        });

        // Questions Table
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->nullable()->constrained('chapters')->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('cascade');
            $table->foreignId('mock_test_id')->nullable()->constrained('mock_tests')->onDelete('cascade');
            $table->text('question_text_hi');
            $table->text('question_text_en')->nullable();
            $table->text('option_a');
            $table->text('option_b');
            $table->text('option_c');
            $table->text('option_d');
            $table->enum('correct_option', ['A', 'B', 'C', 'D']);
            $table->text('explanation_hi')->nullable();
            $table->text('explanation_en')->nullable();
            $table->enum('difficulty_level', ['easy', 'medium', 'hard'])->default('medium');
            $table->timestamps();
        });

        // Test Attempts Table
        Schema::create('test_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('test_type', ['chapter', 'full_mock']);
            $table->foreignId('chapter_id')->nullable()->constrained('chapters')->onDelete('cascade');
            $table->foreignId('mock_test_id')->nullable()->constrained('mock_tests')->onDelete('cascade');
            $table->integer('total_questions')->default(0);
            $table->integer('attempted_questions')->default(0);
            $table->integer('correct_answers')->default(0);
            $table->integer('wrong_answers')->default(0);
            $table->float('score')->default(0);
            $table->integer('total_marks')->default(0);
            $table->float('percentage')->default(0);
            $table->float('accuracy_percentage')->default(0);
            $table->integer('time_taken_seconds')->default(0);
            $table->json('answers_json')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // Certificates Table
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('certificate_code')->unique();
            $table->string('course_name')->default('MP Police Constable GD 2026');
            $table->date('issue_date');
            $table->float('score_achieved')->default(85.0);
            $table->boolean('is_verified')->default(true);
            $table->timestamps();
        });

        // Payments / Transactions Table
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('order_id')->unique();
            $table->decimal('amount', 10, 2)->default(499.00);
            $table->string('payment_method')->default('UPI');
            $table->enum('status', ['PAID', 'CREATED', 'FAILED', 'PENDING'])->default('PAID');
            $table->string('transaction_ref')->nullable();
            $table->timestamps();
        });

        // Study Sessions Table
        Schema::create('study_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('minutes')->default(0);
            $table->date('session_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_sessions');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('test_attempts');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('mock_tests');
        Schema::dropIfExists('chapters');
        Schema::dropIfExists('subjects');
    }
};

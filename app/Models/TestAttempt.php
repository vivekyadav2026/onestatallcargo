<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'test_type',
        'chapter_id',
        'mock_test_id',
        'total_questions',
        'attempted_questions',
        'correct_answers',
        'wrong_answers',
        'score',
        'total_marks',
        'percentage',
        'accuracy_percentage',
        'time_taken_seconds',
        'answers_json',
        'completed_at',
    ];

    protected $casts = [
        'answers_json' => 'array',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function mockTest()
    {
        return $this->belongsTo(MockTest::class);
    }
}

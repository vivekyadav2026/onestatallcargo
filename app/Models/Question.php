<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'chapter_id',
        'subject_id',
        'mock_test_id',
        'question_text_hi',
        'question_text_en',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
        'explanation_hi',
        'explanation_en',
        'difficulty_level',
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function mockTest()
    {
        return $this->belongsTo(MockTest::class);
    }
}

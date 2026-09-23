<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MockTest extends Model
{
    protected $fillable = [
        'test_number',
        'title_hi',
        'title_en',
        'duration_minutes',
        'total_questions',
        'total_marks',
        'is_free',
        'description_hi',
    ];

    protected $casts = [
        'is_free' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function testAttempts()
    {
        return $this->hasMany(TestAttempt::class);
    }
}

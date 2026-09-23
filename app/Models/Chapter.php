<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = [
        'subject_id',
        'chapter_number',
        'title_hi',
        'title_en',
        'description_hi',
        'is_free_preview',
        'duration_minutes',
        'total_questions',
        'notes_content_hi',
        'notes_content_en',
        'pdf_url',
        'order',
    ];

    protected $casts = [
        'is_free_preview' => 'boolean',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
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

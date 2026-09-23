<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name_hi',
        'name_en',
        'code',
        'total_marks',
        'total_chapters',
        'icon',
        'order',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('order');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}

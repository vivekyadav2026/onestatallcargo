<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $guarded = [];

    public function subjects()
    {
        return $this->hasMany(Subject::class)->orderBy('order');
    }

    public function mockTests()
    {
        return $this->hasMany(MockTest::class)->orderBy('test_number');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'class',
        'semester',
        'test1',
        'test2',
        'mid_term',
        'terminal',
    ];

    // Relationship to Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relationship to Subject
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    // Accessor for overall
    public function getOverallAttribute()
    {
        return collect([
            $this->test1,
            $this->test2,
            $this->mid_term,
            $this->terminal,
        ])->filter()->avg();
    }
}

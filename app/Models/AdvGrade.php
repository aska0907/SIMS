<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvGrade extends Model
{
   protected $fillable = [
    'adv_student_id',
    'subject_id',
    'class',
    'semester',
    'combination_id',
    'test1',
    'test2',
    'mid_term',
    'terminal',
];




    public function student()
    {
        return $this->belongsTo(AdvStudent::class, 'adv_student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    public function combination()
{
    return $this->belongsTo(Combination::class, 'combination_id');
}

}

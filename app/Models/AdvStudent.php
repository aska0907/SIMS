<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvStudent extends Model
{
    use HasFactory;

    protected $table = 'adv_students';

    protected $fillable = [
        'full_name',
        'gender',
        'stream',
        'class',
        'registration_id',
    ];


     public function advGrades()
    {
        return $this->hasMany(\App\Models\AdvGrade::class, 'adv_student_id');
    }

    public function combinations()
    {
        return $this->hasMany(\App\Models\AdvancedStudentCombination::class, 'adv_student_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvancedStudentCombination extends Model
{
    use HasFactory;

    protected $table = 'advanced_student_combinations';

    protected $fillable = [
        'adv_student_id',
        'combination_id',
        'class_level',
    ];

    // Relationships

    public function student()
    {
        return $this->belongsTo(AdvStudent::class, 'adv_student_id');
    }

    public function combination()
    {
        return $this->belongsTo(Combination::class, 'combination_id');
    }

  
}

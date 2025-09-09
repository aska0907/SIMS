<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // Relation: combination has many subjects through pivot
   public function subjects()
{
    return $this->belongsToMany(Subject::class, 'combination_subject','combination_id', 'subject_id')
                ->withPivot('type')
                ->withTimestamps();
}

public function combinationSubjects()
{
    return $this->hasMany(\App\Models\CombinationSubject::class);
}


// In Combination.php
public function advStudentSubjects()
{
    return $this->hasMany(\App\Models\AdvStudentSubject::class, 'combination_id');
}

public function advancedStudentCombinations()
{
    return $this->hasMany(\App\Models\AdvancedStudentCombination::class, 'combination_id');
}

}

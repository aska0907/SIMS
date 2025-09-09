<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    
    protected $fillable = [
        'subject_name',
        'subject_code',
        'is_mandatory',
        'mandatory_class',
        'mandatory_classes',
    ];

    protected $casts = [
    'is_mandatory' => 'boolean',
    'mandatory_classes' => 'array', // JSON cast
];


    public function students()
{
    return $this->belongsToMany(Student::class)->withTimestamps();
}
public static function mandatoryForClass(string $class)
{
    // Get all mandatory subjects for the class
    return self::where('is_mandatory', true)
               ->whereJsonContains('mandatory_classes', $class)
               ->pluck('id')
               ->toArray();
}
public function teachers()
{
    return $this->belongsToMany(\App\Models\User::class, 'teacher_subject_class')
                ->withPivot('class')
                ->withTimestamps();
}
public function combinations()
{
    return $this->belongsToMany(\App\Models\Combination::class, 'combination_subject')
                ->withPivot('type')
                ->withTimestamps();
}



}

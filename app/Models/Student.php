<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    
    protected $fillable = [
        'full_name',
        'gender',
        'class',
        'registration_id',
    ];



    public function subjects()
{
    return $this->belongsToMany(Subject::class,'student_subject')->withTimestamps();
}

// App/Models/Student.php
public function grades()
{
    return $this->hasMany(\App\Models\Grade::class);
}



}

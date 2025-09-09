<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSubjectClass extends Model
{
    protected $table = 'teacher_subject_class';

    protected $fillable = ['user_id', 'subject_id', 'class'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    //blade 
    public function students()
    {
        return Student::where('class', $this->class)
                      ->whereHas('subjects', fn($q) => $q->where('subjects.id', $this->subject_id))
                      ->get();
    }

    
}

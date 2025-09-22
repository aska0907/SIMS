<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

 public function setPasswordAttribute($value)
    {
        if (filled($value)) {
            // Only hash if not already hashed
            $this->attributes['password'] = Hash::needsRehash($value)
                ? Hash::make($value)
                : $value;
        }
    }


    public function teachingAssignments()
{
    return $this->belongsToMany(\App\Models\Subject::class, 'teacher_subject_class')
                ->withPivot('class')
                ->withTimestamps();
}


//teacher
public function teacherSubjects()
{
    return $this->hasMany(\App\Models\TeacherSubjectClass::class, 'user_id');
}


}

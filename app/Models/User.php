<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser; // Add this import
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Filament\Panel;


class User extends Authenticatable implements FilamentUser // Add FilamentUser here
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    public function teacherSubjects()
    {
        return $this->hasMany(\App\Models\TeacherSubjectClass::class, 'user_id');
    }
       public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles)
    {
        return in_array($this->role, $roles);
    }
  public function canAccessPanel(Panel $panel): bool
{
    return match ($panel->getId()) {
        'admin' => $this->hasRole('admin'),
        'teacher' => $this->hasAnyRole(['teacher', 'admin']),
        default => false,
    };
}

 
}
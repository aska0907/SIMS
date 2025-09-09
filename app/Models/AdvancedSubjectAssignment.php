<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvancedSubjectAssignment extends Model
{
    use HasFactory;

    protected $table = 'advanced_subject_assignments';

    protected $fillable = [
        'user_id',
        'subject_id',
        'class_level',
        'combination_id',
    ];

    /**
     * Get the teacher (user) assigned.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the subject assigned.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Get the combination related to this assignment.
     */
    public function combination()
    {
        return $this->belongsTo(Combination::class, 'combination_id');
    }
}

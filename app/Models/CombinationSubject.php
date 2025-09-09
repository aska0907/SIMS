<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CombinationSubject extends Model
{
    protected $table = 'combination_subject';

    protected $fillable = [
        'combination_id',
        'subject_id',
        'type',
    ];

    public function combination()
    {
        return $this->belongsTo(Combination::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}

<?php

namespace App\Filament\Resources\TeacherAssignmentResource\Pages;

use App\Filament\Resources\TeacherAssignmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTeacherAssignment extends CreateRecord
{
    protected static string $resource = TeacherAssignmentResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Create new TeacherSubjectClass entry
        return \App\Models\TeacherSubjectClass::create([
            'user_id' => $data['user_id'],
            'subject_id' => $data['subject_id'],
            'class' => $data['class'],
        ]);
    }
}

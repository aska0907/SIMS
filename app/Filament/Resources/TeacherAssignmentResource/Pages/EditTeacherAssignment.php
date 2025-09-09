<?php

namespace App\Filament\Resources\TeacherAssignmentResource\Pages;

use App\Filament\Resources\TeacherAssignmentResource;
use Filament\Resources\Pages\EditRecord;

class EditTeacherAssignment extends EditRecord
{
    protected static string $resource = TeacherAssignmentResource::class;

    /**
     * Correct method signature for Filament >= 3.x
     */
    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $record->update([
            'user_id' => $data['user_id'],
            'subject_id' => $data['subject_id'],
            'class' => $data['class'],
        ]);

        return $record;
    }
}

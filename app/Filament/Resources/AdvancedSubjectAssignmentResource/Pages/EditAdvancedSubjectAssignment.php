<?php

namespace App\Filament\Resources\AdvancedSubjectAssignmentResource\Pages;

use App\Filament\Resources\AdvancedSubjectAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdvancedSubjectAssignment extends EditRecord
{
    protected static string $resource = AdvancedSubjectAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

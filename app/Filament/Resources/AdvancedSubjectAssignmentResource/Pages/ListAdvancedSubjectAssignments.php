<?php

namespace App\Filament\Resources\AdvancedSubjectAssignmentResource\Pages;

use App\Filament\Resources\AdvancedSubjectAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdvancedSubjectAssignments extends ListRecords
{
    protected static string $resource = AdvancedSubjectAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

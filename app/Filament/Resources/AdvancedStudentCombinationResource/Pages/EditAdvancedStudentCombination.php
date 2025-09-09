<?php

namespace App\Filament\Resources\AdvancedStudentCombinationResource\Pages;

use App\Filament\Resources\AdvancedStudentCombinationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdvancedStudentCombination extends EditRecord
{
    protected static string $resource = AdvancedStudentCombinationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

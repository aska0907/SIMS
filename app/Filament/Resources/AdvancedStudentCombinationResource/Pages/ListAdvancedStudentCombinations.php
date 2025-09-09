<?php

namespace App\Filament\Resources\AdvancedStudentCombinationResource\Pages;

use App\Filament\Resources\AdvancedStudentCombinationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdvancedStudentCombinations extends ListRecords
{
    protected static string $resource = AdvancedStudentCombinationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

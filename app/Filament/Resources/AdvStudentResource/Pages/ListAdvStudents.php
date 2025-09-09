<?php

namespace App\Filament\Resources\AdvStudentResource\Pages;

use App\Filament\Resources\AdvStudentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdvStudents extends ListRecords
{
    protected static string $resource = AdvStudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

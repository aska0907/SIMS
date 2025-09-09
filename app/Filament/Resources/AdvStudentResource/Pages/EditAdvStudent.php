<?php

namespace App\Filament\Resources\AdvStudentResource\Pages;

use App\Filament\Resources\AdvStudentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdvStudent extends EditRecord
{
    protected static string $resource = AdvStudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

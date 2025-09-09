<?php

namespace App\Filament\Resources\CombinationResource\Pages;

use App\Filament\Resources\CombinationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCombinations extends ListRecords
{
    protected static string $resource = CombinationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

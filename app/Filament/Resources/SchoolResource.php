<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolResource\Pages;
use App\Models\School;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class SchoolResource extends Resource
{
    protected static ?string $model = School::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
   protected static ?string $navigationGroup = 'School Management';
    protected static ?string $navigationLabel = 'My School';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('School Name')
                    ->required()
                    ->maxLength(255),

                FileUpload::make('logo')
                    ->label('School Logo')
                    ->image()
                    ->directory('schools/logos')
                    ->disk('public')
                    ->imagePreviewHeight('100')
                    ->downloadable()
                    ->nullable(),

                TextInput::make('address')
                    ->label('Address')
                    ->nullable()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->circular()
                    ->getStateUsing(fn ($record) => $record->logo ? asset('storage/' . $record->logo) : null)
                    ->size(50),

                TextColumn::make('name')
                    ->label('School Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('address')
                    ->label('Address')
                    ->sortable()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchools::route('/'),
            'create' => Pages\CreateSchool::route('/create'),
            'edit' => Pages\EditSchool::route('/{record}/edit'),
        ];
    }
}

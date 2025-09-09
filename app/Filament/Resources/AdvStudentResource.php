<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdvStudentResource\Pages;
use App\Models\AdvStudent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class AdvStudentResource extends Resource
{
    protected static ?string $model = AdvStudent::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Advanced Students';
    protected static ?string $pluralLabel = 'Advanced Students';
    protected static ?string $modelLabel = 'Advanced Student';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('full_name')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(255),

                Select::make('gender')
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                    ])
                    ->required(),

                Select::make('stream')
                    ->options([
                        'Science' => 'Science',
                        'Arts' => 'Arts',
                    ])
                    ->required(),

                Select::make('class')
                    ->options([
                        'Form 5' => 'Form 5',
                        'Form 6' => 'Form 6',
                    ])
                    ->required(),

                TextInput::make('registration_id')
                    ->label('Registration ID')
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Full Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('gender')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('stream')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('class')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('registration_id')
                    ->label('Registration ID')
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
            'index' => Pages\ListAdvStudents::route('/'),
            'create' => Pages\CreateAdvStudent::route('/create'),
            'edit' => Pages\EditAdvStudent::route('/{record}/edit'),
        ];
    }
}

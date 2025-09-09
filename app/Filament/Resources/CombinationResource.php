<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CombinationResource\Pages;
use App\Models\Combination;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class CombinationResource extends Resource
{
    protected static ?string $model = Combination::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Combinations';
    protected static ?string $pluralLabel = 'Combinations';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label('Code (e.g. PCB, HGK)')
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('description')
                ->nullable(),

            // IMPORTANT: Bind to the pivot model relationship (hasMany), NOT the belongsToMany.
            Repeater::make('combinationSubjects')
                ->relationship('combinationSubjects') // <-- this targets the pivot table rows
                ->label('Subjects in this Combination')
                ->schema([
                    Select::make('subject_id')
                        ->label('Subject')
                        ->options(fn () => Subject::orderBy('subject_name')->pluck('subject_name', 'id'))
                        ->searchable()
                        ->preload()      // loads all options immediately (no need to type)
                        ->required(),

                    Select::make('type')
                        ->label('Type')
                        ->options([
                            'core'     => 'Core',
                            'optional' => 'Optional',
                        ])
                        ->default('core')
                        ->required(),
                ])
                ->columns(2)
                ->addActionLabel('Add subject'),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Combination')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('description')
                    ->limit(50),

                TextColumn::make('subjects_count')
                    ->label('Total Subjects')
                    ->getStateUsing(fn ($record) => $record->subjects()->count()),
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
            'index'  => Pages\ListCombinations::route('/'),
            'create' => Pages\CreateCombination::route('/create'),
            'edit'   => Pages\EditCombination::route('/{record}/edit'),
        ];
    }
}

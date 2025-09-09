<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubjectResource\Pages;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\MultiSelect;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Subjects';
    protected static ?string $pluralLabel = 'Subjects';
    protected static ?string $modelLabel = 'Subject';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('subject_name')
                    ->label('Subject Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('subject_code')
                    ->label('Subject Code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),

                Toggle::make('is_mandatory')
                    ->label('Is Mandatory?')
                    ->default(false)
                    ->reactive(),

                MultiSelect::make('mandatory_classes')
                    ->label('Mandatory Classes')
                    ->options([
                        'Form 1' => 'Form 1',
                        'Form 2' => 'Form 2',
                        'Form 3' => 'Form 3',
                        'Form 4' => 'Form 4',
                    ])
                    ->visible(fn ($get) => $get('is_mandatory')) // only visible if mandatory
                    ->required(fn ($get) => $get('is_mandatory')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('subject_code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_mandatory')
                    ->label('Mandatory')
                    ->boolean(),

               TextColumn::make('mandatory_classes')
    ->label('Mandatory Classes')
    ->getStateUsing(fn ($record) => is_array($record->mandatory_classes) ? implode(', ', $record->mandatory_classes) : ''),

            ])
            ->filters([])
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
            'index' => Pages\ListSubjects::route('/'),
            'create' => Pages\CreateSubject::route('/create'),
            'edit' => Pages\EditSubject::route('/{record}/edit'),
        ];
    }
}

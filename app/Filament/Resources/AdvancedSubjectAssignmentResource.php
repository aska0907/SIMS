<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdvancedSubjectAssignmentResource\Pages;
use App\Models\AdvancedSubjectAssignment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AdvancedSubjectAssignmentResource extends Resource
{
    protected static ?string $model = AdvancedSubjectAssignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Subject Management';
    protected static ?string $navigationLabel = 'Teacher Assignment(A-Level)';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Teacher')
                    ->relationship('teacher', 'name') // assumes User has 'name' column
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('subject_id')
                    ->label('Subject')
                    ->relationship('subject', 'subject_name')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('combination_id')
                    ->label('Combination')
                    ->relationship('combination', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('class_level')
                    ->label('Class Level')
                    ->options([
                        'Form 5' => 'Form 5',
                        'Form 6' => 'Form 6',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Teacher')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject.subject_name')
                    ->label('Subject')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('combination.name')
                    ->label('Combination')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('class_level')
                    ->label('Class Level')
                    ->colors([
                        'success' => 'Form 5',
                        'warning' => 'Form 6',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Assigned On')
                    ->dateTime('M d, Y H:i'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('class_level')
                    ->options([
                        'Form 5' => 'Form 5',
                        'Form 6' => 'Form 6',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdvancedSubjectAssignments::route('/'),
            'create' => Pages\CreateAdvancedSubjectAssignment::route('/create'),
            'edit' => Pages\EditAdvancedSubjectAssignment::route('/{record}/edit'),
        ];
    }
}

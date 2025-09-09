<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdvancedStudentCombinationResource\Pages;
use App\Models\AdvancedStudentCombination;
use App\Models\AdvStudent;
use App\Models\Combination;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AdvancedStudentCombinationResource extends Resource
{
    protected static ?string $model = AdvancedStudentCombination::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Academics';
    protected static ?string $navigationLabel = 'Student Combinations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Student select (show all students immediately)
                Forms\Components\Select::make('adv_student_id')
                    ->label('Student')
                    ->options(
                        AdvStudent::orderBy('full_name')->pluck('full_name', 'id')
                    )
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $student = AdvStudent::find($state);
                        if ($student) {
                            $set('class_level', $student->class); // auto-fill class_level
                        }
                    }),

                // Combination select (show all combinations immediately)
                Forms\Components\Select::make('combination_id')
                    ->label('Combination')
                    ->options(
                        Combination::orderBy('name')->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),

                // Class level auto-filled from student, read-only
                Forms\Components\TextInput::make('class_level')
                    ->label('Class Level')
                    ->disabled()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.full_name')
                    ->label('Student')
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdvancedStudentCombinations::route('/'),
            'create' => Pages\CreateAdvancedStudentCombination::route('/create'),
            'edit' => Pages\EditAdvancedStudentCombination::route('/{record}/edit'),
        ];
    }
}

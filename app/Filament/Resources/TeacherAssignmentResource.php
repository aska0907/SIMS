<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherAssignmentResource\Pages;
use App\Models\TeacherSubjectClass;
use App\Models\User;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class TeacherAssignmentResource extends Resource
{
    protected static ?string $model = TeacherSubjectClass::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Subject Management';
    protected static ?string $navigationLabel = 'Teacher Assignment(O-Level)';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
 Select::make('user_id')
    ->label('Teacher')
    ->options(
        User::query()
            ->where('role', 'teacher')
            ->pluck('name', 'id')
    )
    ->searchable()
    ->preload()
    ->required(),


                Select::make('subject_id')
                    ->label('Subject')
                    ->options(Subject::all()->pluck('subject_name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('class')
                    ->label('Class')
                    ->options([
                        'Form 1' => 'Form 1',
                        'Form 2' => 'Form 2',
                        'Form 3' => 'Form 3',
                        'Form 4' => 'Form 4',
                     
                    ])
                    ->required(),
            ]);
    }

  public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('teacher.name')
                ->label('Teacher')
                ->sortable()
                ->searchable()
                ->wrap()
                ->toggleable(), // allow hiding/showing dynamically

            TextColumn::make('subject.subject_name')
                ->label('Subject')
                ->sortable()
                ->searchable()
                ->wrap()
                ->toggleable(),

            TextColumn::make('class')
                ->label('Class')
                ->sortable()
                ->searchable()
                ->wrap()
                ->toggleable(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('class')
                ->label('Filter by Class')
                ->options([
                    'Form 1' => 'Form 1',
                    'Form 2' => 'Form 2',
                    'Form 3' => 'Form 3',
                    'Form 4' => 'Form 4',
                  
                ]),

            Tables\Filters\SelectFilter::make('subject_id')
                ->label('Filter by Subject')
                ->relationship('subject', 'subject_name'),
            
            Tables\Filters\SelectFilter::make('user_id')
                ->label('Filter by Teacher')
                ->relationship('teacher', 'name'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ])
        ->defaultSort('teacher.name', 'asc'); // initial sorting
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeacherAssignments::route('/'),
            'create' => Pages\CreateTeacherAssignment::route('/create'),
            'edit' => Pages\EditTeacherAssignment::route('/{record}/edit'),
        ];
    }
}

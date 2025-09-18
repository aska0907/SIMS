<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\Student;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Students';
    protected static ?string $pluralLabel = 'Students';
    protected static ?string $modelLabel = 'Student';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('full_name')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(255),

                Select::make('gender')
                    ->label('Gender')
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                    ])
                    ->required(),

                Select::make('class')
                    ->label('Class')
                    ->options([
                        'Form 1' => 'Form 1',
                        'Form 2' => 'Form 2',
                        'Form 3' => 'Form 3',
                        'Form 4' => 'Form 4',
                    ])
                    ->required()
                    ->reactive(),

                TextInput::make('registration_id')
                    ->label('Student Registration ID')
                    ->nullable()
                    ->maxLength(50),

                FileUpload::make('profile_picture')
                    ->label('Profile Picture')
                    ->image()
                    ->directory('students/profile-pictures')
                    ->disk('public') // important to display correctly
                    ->maxSize(2048) // 2MB
                    ->imagePreviewHeight('150')
                    ->downloadable()
                    ->nullable(),

                MultiSelect::make('subjects')
                    ->label('Subjects')
                    ->options(
                        Subject::pluck('subject_name', 'id')->toArray()
                    )
                    ->columns(2)
                    ->helperText('All mandatory subjects are already selected. Admin can add optional subjects.')
                    ->afterStateHydrated(function ($component, $state, $record) {
                        if ($record) {
                            $component->state($record->subjects->pluck('id')->toArray());
                        } else {
                            $class = request()->get('class') ?? 'Form 1';
                            $mandatorySubjects = Subject::where('is_mandatory', true)
                                ->whereJsonContains('mandatory_classes', $class)
                                ->pluck('id')
                                ->toArray();
                            $component->state($mandatorySubjects);
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('profile_picture')
    ->label('Photo')
    ->circular()
    ->getStateUsing(function ($record) {
        return $record->profile_picture
            ? asset('storage/' . $record->profile_picture)
            : null;
    })
    ->size(50),


                TextColumn::make('full_name')->label('Full Name')->sortable()->searchable(),
                TextColumn::make('gender')->label('Gender')->sortable(),
                TextColumn::make('class')->label('Class')->sortable(),

                TextColumn::make('subjects')
                    ->label('Total Subjects')
                    ->getStateUsing(fn ($record) => $record->subjects->count())
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('class')
                    ->label('Class')
                    ->options([
                        'Form 1' => 'Form 1',
                        'Form 2' => 'Form 2',
                        'Form 3' => 'Form 3',
                        'Form 4' => 'Form 4',
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('subjects');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}

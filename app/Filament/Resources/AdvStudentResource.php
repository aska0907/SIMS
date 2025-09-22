<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdvStudentResource\Pages;
use App\Models\AdvStudent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class AdvStudentResource extends Resource
{
    protected static ?string $model = AdvStudent::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
  protected static ?string $navigationGroup = 'Advaced level Management';
    protected static ?string $navigationLabel = 'Advanced Students';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('profile_picture')
                    ->label('Profile Picture')
                    ->image()
                    ->directory('adv-students/profile-pictures')
                    ->disk('public')
                    ->imagePreviewHeight('150')
                    ->downloadable()
                    ->nullable(),

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
                ImageColumn::make('profile_picture')
                    ->label('Photo')
                    ->circular()
                    ->getStateUsing(fn ($record) => $record->profile_picture ? asset('storage/' . $record->profile_picture) : null)
                    ->size(50),

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

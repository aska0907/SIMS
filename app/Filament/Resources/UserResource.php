<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Manage Users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required(),

                Forms\Components\Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'teacher' => 'Teacher',
                        'cashier' => 'Cashier',
                    ])
                    ->default('teacher')
                    ->required(),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn (string $context): bool => $context === 'create')
                    ->label('Password'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('role'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                Tables\Actions\Action::make('resetPassword')
                    ->label('Reset Password')
                    ->form([
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required(),
                    ])
                    ->action(function (User $record, array $data): void {
                        // Password mutator in User model will hash automatically
                        $record->update([
                            'password' => $data['password'],
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\UserResource\Pages;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Community';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('github_login')
                    ->required()
                    ->maxLength(255)
                    ->label('GitHub'),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
            ])
            ->columns(1);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label('ID')
                    ->weight(FontWeight::Bold),

                Tables\Columns\ImageColumn::make('gravatar')
                    ->circular()
                    ->getStateUsing(fn (User $record) => $record->avatar),

                Tables\Columns\TextColumn::make('name'),

                Tables\Columns\TextColumn::make('github_login')
                    ->searchable()
                    ->label('GitHub'),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->label('Registration Date'),

                Tables\Columns\TextColumn::make('last_login_at')
                    ->date()
                    ->sortable()
                    ->label('Last Login Date')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('')
                    ->button()
                    ->outlined()
                    ->size('xs'),

                Tables\Actions\DeleteAction::make()
                    ->icon('')
                    ->button()
                    ->outlined()
                    ->size('xs'),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('activities')
                        ->url(fn (Model $record) => self::getUrl('activities', compact('record')))
                        ->icon('heroicon-o-list-bullet'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages() : array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'activities' => Pages\ListUserActivities::route('/{record}/activities'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes() : array
    {
        return ['name', 'github_login', 'email'];
    }

    public static function getGlobalSearchResultDetails(Model $record) : array
    {
        return [
            'Email' => $record->email,
        ];
    }
}

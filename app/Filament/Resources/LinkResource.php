<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Link;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LinkResource\Pages;

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;

    protected static ?string $navigationGroup = 'Community';

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->columnSpanFull()
                    ->label('Sender'),

                Forms\Components\TextInput::make('url')
                    ->required()
                    ->url()
                    ->maxLength(255)
                    ->label('URL')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('image_url')
                    ->url()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Forms\Components\DateTimePicker::make('is_approved')
                    ->timezone('Europe/Paris')
                    ->native(false)
                    ->label('Approved At'),

                Forms\Components\DateTimePicker::make('is_declined')
                    ->timezone('Europe/Paris')
                    ->native(false)
                    ->label('Declined At'),
            ]);
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

                Tables\Columns\ImageColumn::make('image_url')
                    ->defaultImageUrl(secure_asset('img/placeholder.svg'))
                    ->width(107)
                    ->height(80)
                    ->label('Image'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('url')
                    ->searchable()
                    ->limit(30)
                    ->label('URL'),

                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->label('Sender'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) : string => match ($state) {
                        'Approved' => 'success',
                        'Declined' => 'danger',
                        default => 'gray',
                    })
                    ->getStateUsing(function (Model $record) {
                        if ($record->is_approved) {
                            return 'Approved';
                        }

                        if ($record->is_declined) {
                            return 'Declined';
                        }

                        return 'Pending';
                    })
                    ->label('Status'),

                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->label('Submitted Date'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'approved' => 'Approved',
                        'declined' => 'Declined',
                        'pending' => 'Pending',
                    ])
                    ->query(fn (Builder $query, array $data) => match ($data['value']) {
                        'approved' => $query->approved(),
                        'declined' => $query->declined(),
                        'pending' => $query->pending(),
                        default => $query,
                    })
                    ->default('pending'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->action(function (Link $record, array $data) {
                        $record->approve($data['notes']);

                        Notification::make()
                            ->title('The link has been approved.')
                            ->success()
                            ->send();
                    })
                    ->modalHeading('Approve Link')
                    ->modalSubmitActionLabel('Approve')
                    ->hidden(fn (Link $record) => $record->isApproved())
                    ->color('success')
                    ->button()
                    ->outlined()
                    ->size('xs'),

                Tables\Actions\Action::make('decline')
                    ->action(fn (Link $record) => $record->decline())
                    ->hidden(fn (Link $record) => $record->isDeclined())
                    ->color('danger')
                    ->button()
                    ->outlined()
                    ->size('xs'),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('Put back in pending')
                        ->action(fn (Link $record) => $record->update([
                            'is_approved' => null,
                            'is_declined' => null,
                        ]))
                        ->icon('heroicon-o-queue-list')
                        ->hidden(fn (Link $record) => is_null($record->is_approved) && is_null($record->is_declined)),

                    Tables\Actions\Action::make('activities')
                        ->url(fn (Model $record) => self::getUrl('activities', compact('record')))
                        ->icon('heroicon-o-list-bullet'),

                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil'),

                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash'),
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
            'index' => Pages\ListLinks::route('/'),
            'activities' => Pages\ListLinkActivities::route('/{record}/activities'),
            'create' => Pages\CreateLink::route('/create'),
            'edit' => Pages\EditLink::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes() : array
    {
        return ['user.name', 'url', 'title', 'description'];
    }

    public static function getGlobalSearchResultDetails($record) : array
    {
        return [
            'From' => $record->user->name,
            'URL' => $record->url,
        ];
    }
}

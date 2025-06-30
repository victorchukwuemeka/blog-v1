<?php

namespace App\Filament\Resources;

use App\Models\Link;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\LinkResource\Pages\EditLink;
use App\Filament\Resources\LinkResource\Pages\ListLinks;
use App\Filament\Resources\LinkResource\Pages\CreateLink;

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Community';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-link';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->columnSpanFull()
                    ->label('Sender'),

                TextInput::make('url')
                    ->required()
                    ->url()
                    ->maxLength(255)
                    ->label('URL')
                    ->columnSpanFull(),

                TextInput::make('image_url')
                    ->url()
                    ->columnSpanFull(),

                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                DateTimePicker::make('is_approved')
                    ->timezone('Europe/Paris')
                    ->native(false)
                    ->label('Approved At'),

                DateTimePicker::make('is_declined')
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
                TextColumn::make('id')
                    ->sortable()
                    ->label('ID')
                    ->weight(FontWeight::Bold),

                ImageColumn::make('image_url')
                    ->imageWidth(107)
                    ->imageHeight(80)
                    ->label('Image'),

                TextColumn::make('title')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('url')
                    ->searchable()
                    ->limit(30)
                    ->label('URL'),

                TextColumn::make('user.name')
                    ->searchable()
                    ->label('Sender'),

                TextColumn::make('status')
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

                TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->label('Submitted Date'),
            ])
            ->filters([
                SelectFilter::make('status')
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
            ->recordActions([
                Action::make('approve')
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

                Action::make('decline')
                    ->action(fn (Link $record) => $record->decline())
                    ->hidden(fn (Link $record) => $record->isDeclined())
                    ->color('danger')
                    ->button()
                    ->outlined()
                    ->size('xs'),

                ActionGroup::make([
                    Action::make('Put back in pending')
                        ->action(fn (Link $record) => $record->update([
                            'is_approved' => null,
                            'is_declined' => null,
                        ]))
                        ->icon('heroicon-o-queue-list')
                        ->hidden(fn (Link $record) => is_null($record->is_approved) && is_null($record->is_declined)),

                    EditAction::make()
                        ->icon('heroicon-o-pencil'),

                    DeleteAction::make()
                        ->icon('heroicon-o-trash'),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages() : array
    {
        return [
            'index' => ListLinks::route('/'),
            'create' => CreateLink::route('/create'),
            'edit' => EditLink::route('/{record}/edit'),
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

<?php

namespace App\Filament\Resources;

use App\Str;
use App\Models\Post;
use Filament\Tables\Table;
use Illuminate\Support\Js;
use App\Jobs\RecommendPosts;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\Page;
use Filament\Actions\RestoreAction;
use Illuminate\Support\Facades\Date;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Components\Group;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\Action as TableAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Pages\Enums\SubNavigationPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PostResource\Pages\EditPost;
use App\Filament\Resources\PostResource\Pages\ListPosts;
use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\PostResource\Pages\ManagePostComments;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Blog';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([
                Group::make([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                            // Only update slug if it hasn't been manually customized.
                            if (($get('slug') ?? '') !== Str::slug($old)) {
                                return;
                            }

                            // If the slug hasn't been customized, update it to match the new title
                            $set('slug', Str::slug($state));
                        }),

                    MarkdownEditor::make('content')
                        ->fileAttachmentsDisk('cloudflare-images')
                        ->fileAttachmentsDirectory('images/posts')
                        ->required()
                        ->columnSpanFull(),
                ])
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 8,
                    ]),

                Group::make([
                    FileUpload::make('image_path')
                        ->image()
                        ->disk(fn (Post $record) => $record->image_disk ?? 'cloudflare-images')
                        ->directory('images/posts')
                        ->required()
                        ->columnSpanFull()
                        ->label('Image')
                        ->helperText('Resizing and compression are applied automatically.'),

                    TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->helperText('An exact match with the main keyword is preferred.'),

                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->required()
                        ->default(auth()->id())
                        ->searchable()
                        ->label('Author'),

                    Select::make('categories')
                        ->relationship(name: 'categories', titleAttribute: 'name')
                        ->multiple()
                        ->searchable()
                        ->createOptionForm(CategoryResource::form($schema)->getComponents()),

                    TextInput::make('serp_title')
                        ->maxLength(255)
                        ->label('SERP Title')
                        ->helperText('This appears in the search results.'),

                    Textarea::make('description')
                        ->maxLength(65535)
                        ->columnSpanFull(),

                    Toggle::make('is_commercial')
                        ->label('Commercial')
                        ->default(false)
                        ->helperText('This gives readers a focused layout.')
                        ->columnSpanFull(),

                    TextInput::make('canonical_url')
                        ->nullable()
                        ->maxLength(255)
                        ->rules('url')
                        ->label('Canonical URL')
                        ->columnSpanFull(),

                    DateTimePicker::make('published_at')
                        ->timezone('Europe/Paris')
                        ->native(false)
                        ->seconds(false)
                        ->closeOnDateSelection()
                        ->placeholder(now())
                        ->defaultFocusedDate(now())
                        ->reactive()
                        ->afterStateUpdated(function (DateTimePicker $component, $state) {
                            if (blank($state)) {
                                return;
                            }

                            $now = Date::now();

                            $date = Date::parse($state)->setTime($now->hour, $now->minute, $now->second);

                            $component->state($date);
                        })
                        ->label('Publication Date'),

                    DateTimePicker::make('modified_at')
                        ->timezone('Europe/Paris')
                        ->native(false)
                        ->seconds(false)
                        ->placeholder(now())
                        ->defaultFocusedDate(now())
                        ->closeOnDateSelection()
                        ->reactive()
                        ->afterStateUpdated(function (DateTimePicker $component, $state) {
                            if (blank($state)) {
                                return;
                            }

                            $now = Date::now();

                            $date = Date::parse($state)->setTime($now->hour, $now->minute, $now->second);

                            $component->state($date);
                        })
                        ->label('Last Modification Date'),
                ])
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 4,
                    ]),
            ])
            ->columns(12);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                ImageColumn::make('image_path')
                    ->disk(fn (Post $record) => $record->image_disk ?? 'cloudflare-images')
                    ->imageWidth(107)
                    ->imageHeight(80)
                    ->default(secure_asset('img/placeholder.png'))
                    ->label('Image'),

                TextColumn::make('title')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('user.name')
                    ->searchable()
                    ->label('Author'),

                TextColumn::make('canonical_url')
                    ->default('-')
                    ->label('Canonical URL')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('categories')
                    ->getStateUsing(fn (Post $record) => $record->categories->pluck('name')->join(','))
                    ->badge()
                    ->separator(','),

                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Publication Date'),

                TextColumn::make('modified_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Modification Date')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('image_path')
                    ->nullable()
                    ->label('Image')
                    ->placeholder('Both')
                    ->trueLabel('With image')
                    ->falseLabel('Without image')
                    ->queries(
                        blank: fn (Builder $query) => $query,
                        true: fn (Builder $query) => $query->whereNotNull('image_path'),
                        false: fn (Builder $query) => $query->whereNull('image_path'),
                    ),

                SelectFilter::make('link_association')
                    ->label('Link Association')
                    ->options([
                        'with_link' => 'With link',
                        'without_link' => 'Without link',
                    ])
                    ->query(fn (Builder $query, array $data) => match ($data['value']) {
                        'with_link' => $query->whereHas('link'),
                        'without_link' => $query->whereDoesntHave('link'),
                        default => $query,
                    }),

                // New: Filter posts that haven't been modified for a year or more
                TernaryFilter::make('updated_stale')
                    ->nullable()
                    ->label('Updated 1+ Year Ago')
                    ->placeholder('Both')
                    ->trueLabel('Yes')
                    ->falseLabel('No')
                    ->queries(
                        blank: fn (Builder $query) => $query,
                        true: fn (Builder $query) => $query->where(function (Builder $query) {
                            $query->whereNull('modified_at')
                                ->orWhere('modified_at', '<=', Date::now()->subYear());
                        }),
                        false: fn (Builder $query) => $query->where(function (Builder $query) {
                            $query->whereNotNull('modified_at')
                                ->where('modified_at', '>', Date::now()->subYear());
                        }),
                    ),

                TernaryFilter::make('published_at')
                    ->nullable()
                    ->label('Published Status')
                    ->placeholder('Both')
                    ->trueLabel('Published')
                    ->falseLabel('Draft')
                    ->queries(
                        blank: fn (Builder $query) => $query,
                        true: fn (Builder $query) => $query->whereNotNull('published_at'),
                        false: fn (Builder $query) => $query->whereNull('published_at'),
                    ),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    TableAction::make('open')
                        ->label('Open')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->url(fn (Post $record) => route('posts.show', $record), shouldOpenInNewTab: true),

                    TableAction::make('copy')
                        ->label('Copy as Markdown')
                        ->icon('heroicon-o-clipboard-document')
                        ->alpineClickHandler(fn (Post $record) => 'window.navigator.clipboard.writeText(' . Js::from($record->toMarkdown()) . ')'),

                    Action::make('recommendations')
                        ->action(function (Post $record) {
                            RecommendPosts::dispatch($record);

                            Notification::make()
                                ->title('A job has been queued to refresh the recommendations.')
                                ->success()
                                ->send();
                        })
                        ->icon('heroicon-o-arrow-path'),

                    EditAction::make()
                        ->icon('heroicon-o-pencil-square'),

                    DeleteAction::make()
                        ->icon('heroicon-o-trash'),

                    ForceDeleteAction::make(),

                    RestoreAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages() : array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
            'comments' => ManagePostComments::route('/{record}/comments'),
        ];
    }

    public static function getGloballySearchableAttributes() : array
    {
        return ['user.name', 'title', 'serp_title', 'slug', 'content', 'description', 'canonical_url'];
    }

    public static function getGlobalSearchResultDetails(Model $record) : array
    {
        return ['Author' => $record->user->name];
    }

    public static function getRecordSubNavigation(Page $page) : array
    {
        return $page->generateNavigationItems([
            EditPost::class,
            ManagePostComments::class,
        ]);
    }

    public static function getSubNavigationPosition() : SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }

    public static function getRecordRouteBindingEloquentQuery() : Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

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
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Enums\FontWeight;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\Action as TableAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use App\Filament\Resources\PostResource\Pages\EditPost;
use App\Filament\Resources\PostResource\Pages\ListPosts;
use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\PostResource\RelationManagers\CategoriesRelationManager;

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
                FileUpload::make('image_path')
                    ->image()
                    ->disk(fn (Get $get) => $get('image_disk') ?? config('filesystems.default'))
                    ->columnSpanFull()
                    ->label('Image')
                    ->requiredWithAll('image_disk'),

                Select::make('image_disk')
                    ->options(collect(config('filesystems.disks'))->mapWithKeys(fn (array $disk, string $key) => [$key => $key]))
                    ->rules(['nullable', 'string', 'in:' . implode(',', array_keys(config('filesystems.disks')))])
                    ->requiredWithAll('image_path')
                    ->columnSpanFull()
                    ->label('Image Disk'),

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

                TextInput::make('slug')
                    ->required()
                    ->maxLength(255),

                MarkdownEditor::make('content')
                    ->required()
                    ->columnSpanFull(),

                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->label('Author'),

                TextInput::make('serp_title')
                    ->maxLength(255)
                    ->label('SERP Title')
                    ->helperText('This is the title that will appear in the search results.'),

                Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                TextInput::make('canonical_url')
                    ->nullable()
                    ->maxLength(255)
                    ->rules('url')
                    ->label('Canonical URL'),

                DateTimePicker::make('published_at')
                    ->timezone('Europe/Paris')
                    ->native(false),

                DateTimePicker::make('modified_at')
                    ->timezone('Europe/Paris')
                    ->native(false),
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

                ImageColumn::make('image_path')
                    ->disk(fn (Post $record) => $record->image_disk ?? 'public')
                    ->imageWidth(107)
                    ->imageHeight(80)
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
                    ->date()
                    ->sortable()
                    ->label('Publication Date'),

                TextColumn::make('modified_at')
                    ->date()
                    ->sortable()
                    ->label('Modification Date')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
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
                ]),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
                ForceDeleteBulkAction::make(),
                RestoreBulkAction::make(),
            ]);
    }

    public static function getPages() : array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }

    public static function getRelations() : array
    {
        return [
            CategoriesRelationManager::class,
        ];
    }

    public static function getGloballySearchableAttributes() : array
    {
        return ['user.name', 'title', 'slug', 'content', 'description', 'canonical_url'];
    }

    public static function getGlobalSearchResultDetails(Model $record) : array
    {
        return ['Author' => $record->user->name];
    }
}

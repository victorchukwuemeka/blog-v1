<?php

namespace App\Filament\Resources;

use App\Str;
use App\Models\Category;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Utilities\Set;
use App\Filament\Resources\CategoryResource\Pages\EditCategory;
use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Resources\CategoryResource\Pages\ListCategories;
use App\Filament\Resources\CategoryResource\RelationManagers\PostsRelationManager;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Blog';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->required()
                    ->maxLength(255),

                MarkdownEditor::make('content')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('slug')
                    ->searchable(),

                TextColumn::make('posts_count')
                    ->label('Posts')
                    ->counts('posts')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('')
                    ->button()
                    ->outlined()
                    ->size('xs'),

                DeleteAction::make()
                    ->icon('')
                    ->button()
                    ->outlined()
                    ->size('xs'),
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
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getRelations() : array
    {
        return [
            PostsRelationManager::class,
        ];
    }

    public static function getGloballySearchableAttributes() : array
    {
        return ['name', 'slug', 'content'];
    }

    public static function getEloquentQuery() : Builder
    {
        return parent::getEloquentQuery()
            ->withCount('posts');
    }
}

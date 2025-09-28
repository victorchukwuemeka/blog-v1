<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Filament\Resources\Posts\PostResource;
use Filament\Resources\RelationManagers\RelationManager;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Schema $schema) : Schema
    {
        return PostResource::form($schema);
    }

    public function table(Table $table) : Table
    {
        return PostResource::table($table)->defaultSort(null);
    }
}

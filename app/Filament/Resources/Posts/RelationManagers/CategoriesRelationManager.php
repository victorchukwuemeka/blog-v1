<?php

namespace App\Filament\Resources\Posts\RelationManagers;

use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Filament\Resources\CategoryResource;
use Filament\Resources\RelationManagers\RelationManager;

class CategoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'categories';

    public function form(Schema $schema) : Schema
    {
        return CategoryResource::form($schema);
    }

    public function table(Table $table) : Table
    {
        return CategoryResource::table($table)->defaultSort(null);
    }
}

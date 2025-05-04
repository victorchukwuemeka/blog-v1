<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\Resources\CategoryResource;
use Filament\Resources\RelationManagers\RelationManager;

class CategoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'categories';

    public function form(Form $form) : Form
    {
        return CategoryResource::form($form);
    }

    public function table(Table $table) : Table
    {
        return CategoryResource::table($table)->defaultSort(null);
    }
}

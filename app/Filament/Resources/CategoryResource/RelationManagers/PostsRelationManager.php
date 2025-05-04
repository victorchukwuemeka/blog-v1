<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\Resources\PostResource;
use Filament\Resources\RelationManagers\RelationManager;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form) : Form
    {
        return PostResource::form($form);
    }

    public function table(Table $table) : Table
    {
        return PostResource::table($table)->defaultSort(null);
    }
}

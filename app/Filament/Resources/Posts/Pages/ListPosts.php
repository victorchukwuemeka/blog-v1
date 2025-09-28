<?php

namespace App\Filament\Resources\Posts\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Posts\PostResource;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions() : array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getTableQuery() : Builder
    {
        return parent::getTableQuery()
            ->select([
                'id',
                'user_id',
                'image_path',
                'image_disk',
                'title',
                'slug',
                'canonical_url',
                'sessions_count',
                'published_at',
                'modified_at',
                'is_commercial',
                'deleted_at',
            ])
            ->with([
                'user:id,name',
                'categories:id,name',
            ]);
    }
}

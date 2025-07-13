<?php

namespace App\Filament\Resources\PostResource\Pages;

use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Filament\Resources\PostResource;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\CommentResource;
use Filament\Resources\Pages\ManageRelatedRecords;

class ManagePostComments extends ManageRelatedRecords
{
    protected static string $resource = PostResource::class;

    protected static string $relationship = 'comments';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    public function form(Schema $schema) : Schema
    {
        return CommentResource::form($schema);
    }

    public function table(Table $table) : Table
    {
        return CommentResource::table($table);
    }

    public function getTitle() : string|Htmlable
    {
        $recordTitle = $this->getRecordTitle();

        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;

        return "Manage \"{$recordTitle}\" comments";
    }

    public function getBreadcrumb() : string
    {
        return 'Comments';
    }

    public static function getNavigationLabel() : string
    {
        return 'Comments';
    }
}

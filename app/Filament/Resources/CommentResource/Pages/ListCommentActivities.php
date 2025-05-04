<?php

namespace App\Filament\Resources\CommentResource\Pages;

use App\Filament\Resources\CommentResource;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class ListCommentActivities extends ListActivities
{
    protected static string $resource = CommentResource::class;
}

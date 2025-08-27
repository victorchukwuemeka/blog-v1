<?php

namespace App\Filament\Resources\Revisions\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Revisions\RevisionResource;

class ListRevisions extends ListRecords
{
    protected static string $resource = RevisionResource::class;

    protected function getHeaderActions() : array
    {
        return [];
    }
}

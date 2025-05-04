<?php

namespace App\Filament\Resources\LinkResource\Pages;

use Filament\Actions;
use App\Filament\Resources\LinkResource;
use Filament\Resources\Pages\ListRecords;

class ListLinks extends ListRecords
{
    protected static string $resource = LinkResource::class;

    protected function getHeaderActions() : array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\LinkResource\Pages;

use Filament\Actions;
use App\Filament\Resources\LinkResource;
use Filament\Resources\Pages\EditRecord;

class EditLink extends EditRecord
{
    protected static string $resource = LinkResource::class;

    protected function getHeaderActions() : array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

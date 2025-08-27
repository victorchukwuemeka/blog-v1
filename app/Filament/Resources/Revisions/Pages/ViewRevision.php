<?php

namespace App\Filament\Resources\Revisions\Pages;

use App\Models\Revision;
use Illuminate\Support\Js;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Revisions\RevisionResource;

class ViewRevision extends ViewRecord
{
    protected static string $resource = RevisionResource::class;

    protected function getHeaderActions() : array
    {
        return [
            Action::make('copy')
                ->label('Copy as Markdown')
                ->icon('heroicon-o-clipboard-document')
                ->alpineClickHandler(fn (Revision $record) => 'window.navigator.clipboard.writeText(' . Js::from($record->data['content']) . ')'),

            DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\Revisions\Pages;

use App\Models\Revision;
use Illuminate\Support\Js;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
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
                ->tooltip('Copy as Markdown')
                ->hiddenLabel(true)
                ->color('gray')
                ->icon('heroicon-o-clipboard-document')
                ->alpineClickHandler(fn (Revision $record) => 'window.navigator.clipboard.writeText(' . Js::from($record->data['content']) . ')'),

            Action::make('complete')
                ->label('Mark as completed')
                ->tooltip('Mark as completed')
                ->hiddenLabel(true)
                ->icon('heroicon-o-check')
                ->action(function (Revision $record) {
                    $record->update(['completed_at' => now()]);

                    Notification::make()
                        ->title('Revision marked as completed')
                        ->success()
                        ->send();

                    $this->redirect(RevisionResource::getUrl('index'));
                }),

            DeleteAction::make(),
        ];
    }
}

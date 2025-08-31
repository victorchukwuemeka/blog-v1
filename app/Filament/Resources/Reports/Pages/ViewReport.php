<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Models\Report;
use App\Jobs\RevisePost;
use Illuminate\Support\Js;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Reports\ReportResource;

class ViewReport extends ViewRecord
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions() : array
    {
        return [
            Action::make('copy')
                ->label('Copy as Markdown')
                ->hiddenLabel(true)
                ->icon('heroicon-o-clipboard-document')
                ->color('gray')
                ->alpineClickHandler(fn (Report $record) => 'window.navigator.clipboard.writeText(' . Js::from($record->content) . ')'),

            Action::make('Implement')
                ->schema([
                    Textarea::make('additional_instructions')
                        ->nullable(),
                ])
                ->modalSubmitActionLabel('Implement')
                ->action(function (Report $record, array $data) {
                    RevisePost::dispatch($record->post, $record, $data['additional_instructions']);

                    Notification::make()
                        ->title('The post has been queued for revision.')
                        ->success()
                        ->send();
                }),
        ];
    }
}

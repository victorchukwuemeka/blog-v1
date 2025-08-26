<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Models\Report;
use Illuminate\Support\Js;
use Filament\Actions\Action;
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
                ->icon('heroicon-o-clipboard-document')
                ->alpineClickHandler(fn (Report $record) => 'window.navigator.clipboard.writeText(' . Js::from($record->content) . ')'),
        ];
    }
}

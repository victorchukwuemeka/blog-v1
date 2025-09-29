<?php

namespace App\Filament\Resources\Categories\Actions;

use App\Models\Category;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use App\Jobs\GenerateCategoryPage;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use App\Filament\Resources\Categories\Pages\EditCategory;

class RecordActions
{
    public static function configure() : array
    {
        return [
            Action::make('open')
                ->label('Open')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn (Category $record) => route('categories.show', $record), shouldOpenInNewTab: true),

            Action::make('Generate category page')
                ->schema([
                    Textarea::make('additional_instructions')
                        ->nullable(),
                ])
                ->action(function (Category $record, array $data) {
                    GenerateCategoryPage::dispatch($record, $data['additional_instructions'] ?? null);

                    Notification::make()
                        ->title('A job has been queued to generate the category page.')
                        ->success()
                        ->send();
                })
                ->icon('heroicon-o-arrow-path'),

            EditAction::make()
                ->hidden(fn ($livewire) => $livewire instanceof EditCategory),

            DeleteAction::make(),
        ];
    }
}

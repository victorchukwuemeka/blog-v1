<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Models\Post;
use Livewire\Attributes\Js;
use App\Jobs\RecommendPosts;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions() : array
    {
        return [
            DeleteAction::make(),

            Action::make('copy')
                ->label('Copy')
                ->button()
                ->outlined()
                ->size('xs')
                ->tooltip('Copy the article in Markdown format')
                ->alpineClickHandler(fn (Post $record) => 'window.navigator.clipboard.writeText(' . Js::from($record->toMarkdown()) . ')'),
        ];
    }

    protected function afterSave() : void
    {
        if (! $this->record->recommendations) {
            RecommendPosts::dispatch($this->record);
        }
    }
}

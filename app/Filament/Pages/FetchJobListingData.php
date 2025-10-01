<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Jobs\FetchJobListingData as FetchJobListingDataJob;

class FetchJobListingData extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-plus';

    protected static string|\UnitEnum|null $navigationGroup = 'Pages';

    public static function getNavigationLabel() : string
    {
        return 'New job listing';
    }

    public function getTitle() : string|Htmlable
    {
        return 'New job listing';
    }

    protected static ?string $slug = 'fetch-job-listing';

    protected string $view = 'filament.pages.fetch-job-listing-data';

    public array $data = [];

    public function mount() : void
    {
        $this->form->fill();
    }

    public function form(Schema $schema) : Schema
    {
        return $schema
            ->components([
                TextInput::make('url')
                    ->label('URL')
                    ->url()
                    ->required()
                    ->prefixIcon('heroicon-m-link'),
            ])
            ->statePath('data');
    }

    public function submit() : void
    {
        $state = $this->form->getState();

        FetchJobListingDataJob::dispatch($state['url']);

        Notification::make()
            ->title('Fetching job listing…')
            ->body('The job listing is being fetched in the background.')
            ->success()
            ->send();

        $this->reset('data');
        $this->form->fill();
    }

    protected function getFormActions() : array
    {
        return [
            Action::make('submit')
                ->label('Fetch')
                ->submit('submit')
                ->icon('heroicon-m-arrow-path'),
        ];
    }
}

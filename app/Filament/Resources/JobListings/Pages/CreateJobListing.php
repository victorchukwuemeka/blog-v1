<?php

namespace App\Filament\Resources\JobListings\Pages;

use Filament\Schemas\Schema;
use App\Jobs\FetchJobListingData;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\JobListings\JobListingResource;

class CreateJobListing extends CreateRecord
{
    protected static string $resource = JobListingResource::class;

    public function form(Schema $schema) : Schema
    {
        return $schema->components([
            TextInput::make('url')
                ->required()
                ->rules(['url'])
                ->label('URL')
                ->columnSpanFull(),
        ]);
    }

    protected function getSubmitFormLivewireMethodName() : string
    {
        return 'fetch';
    }

    public function fetch() : void
    {
        $this->validate();

        $data = $this->form->getState();

        FetchJobListingData::dispatch($data['url']);

        Notification::make()
            ->title('Fetching the job listing…')
            ->body('The job listing is being fetched in the background. This may take a while.')
            ->success()
            ->send();

        $this->redirect($this->getResourceUrl());
    }
}

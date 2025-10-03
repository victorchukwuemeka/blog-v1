<?php

namespace App\Filament\Resources\JobListings\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class JobListingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('company_id')
                    ->relationship('company', 'name')
                    ->required(),

                TextInput::make('url')
                    ->url()
                    ->required(),

                TextInput::make('source')
                    ->required(),

                TextInput::make('language')
                    ->required(),

                TextInput::make('title')
                    ->required(),

                TextInput::make('slug')
                    ->required(),

                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('technologies')
                    ->nullable(),

                TextInput::make('locations')
                    ->nullable(),

                TextInput::make('setting')
                    ->required(),

                TextInput::make('min_salary')
                    ->numeric(),

                TextInput::make('max_salary')
                    ->numeric(),

                TextInput::make('currency'),

                Toggle::make('equity')
                    ->required(),

                TextInput::make('how_to_apply')
                    ->nullable(),

                TextInput::make('perks')
                    ->nullable(),

                TextInput::make('interview_process')
                    ->nullable(),
            ]);
    }
}

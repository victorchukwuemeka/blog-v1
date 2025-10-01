<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class CompanyForm
{
    public static function configure(Schema $schema) : Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                TextInput::make('slug')
                    ->required(),

                TextInput::make('url')
                    ->url()
                    ->label('URL'),

                TextInput::make('logo'),

                Textarea::make('about')
                    ->columnSpanFull(),
            ]);
    }
}

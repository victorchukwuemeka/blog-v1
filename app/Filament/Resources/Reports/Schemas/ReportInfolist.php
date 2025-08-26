<?php

namespace App\Filament\Resources\Reports\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\TextEntry;

class ReportInfolist
{
    public static function configure(Schema $schema) : Schema
    {
        return $schema
            ->components([
                TextEntry::make('post.title')
                    ->label('Post')
                    ->columnSpanFull(),

                TextEntry::make('content')
                    ->markdown()
                    ->columnSpan(2),

                Grid::make()
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->label('Creation Date'),

                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->label('Last Modification Date'),
                    ])
                    ->columnSpanFull(),
            ])
            ->columns(3);
    }
}

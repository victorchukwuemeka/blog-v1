<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Models\User;
use Filament\Infolists\Infolist;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function infolist(Infolist $infolist) : Infolist
    {
        return $infolist
            ->schema([
                ImageEntry::make('gravatar')
                    ->label('Avatar')
                    ->circular()
                    ->getStateUsing(fn (User $record) => $record->present()->gravatar())
                    ->columnSpanFull(),

                TextEntry::make('id')
                    ->label('ID'),

                TextEntry::make('name'),

                TextEntry::make('github_login')
                    ->label('GitHub'),

                TextEntry::make('email'),

                TextEntry::make('created_at')
                    ->date()
                    ->label('Registration Date'),

                TextEntry::make('last_login_at')
                    ->date()
                    ->label('Last Login Date'),
            ]);
    }
}

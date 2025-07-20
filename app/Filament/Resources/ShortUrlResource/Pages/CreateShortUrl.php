<?php

namespace App\Filament\Resources\ShortUrlResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ShortUrlResource;

class CreateShortUrl extends CreateRecord
{
    protected static string $resource = ShortUrlResource::class;
}

<?php

namespace App\Filament\Forms\Components;

use Spatie\Image\Image;
use Spatie\Image\Enums\Fit;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MarkdownEditor extends \Filament\Forms\Components\MarkdownEditor
{
    public function saveUploadedFileAttachment(TemporaryUploadedFile $attachment) : ?string
    {
        $image = Image::load($attachment->path());

        if ($image->getWidth() > 1500 || $image->getHeight() > 1500) {
            $image->fit(Fit::Contain, 1500, 1500);
        }

        $image
            ->quality(70)
            ->optimize()
            ->save($attachment->path());

        return parent::saveUploadedFileAttachment($attachment);
    }
}

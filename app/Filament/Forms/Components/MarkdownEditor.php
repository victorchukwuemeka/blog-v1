<?php

namespace App\Filament\Forms\Components;

use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MarkdownEditor extends \Filament\Forms\Components\MarkdownEditor
{
    public function saveUploadedFileAttachment(TemporaryUploadedFile $attachment) : ?string
    {
        return parent::saveUploadedFileAttachment($attachment);
    }
}

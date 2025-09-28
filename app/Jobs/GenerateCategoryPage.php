<?php

namespace App\Jobs;

use App\Models\Category;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateCategoryPage implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Category $category,
        public ?string $additionalInstructions = null,
    ) {}

    public function handle() : void
    {
        app(\App\Actions\GenerateCategoryPage::class)->generate(
            $this->category,
            $this->additionalInstructions
        );
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use App\Jobs\GenerateCategoryPage;

class RefreshCategoryPagesCommand extends Command
{
    protected $signature = 'app:refresh-category-pages';

    protected $description = 'Refresh the content of category pages.';

    public function handle() : void
    {
        Category::query()
            // If modified_at is null, it means I didn't greenlight the generation yet.
            ->whereNotNull('modified_at')
            ->where('modified_at', '<=', now()->subWeek())
            ->cursor()
            ->each(function (Category $category) {
                GenerateCategoryPage::dispatch($category);

                $this->info("Queued category page generation for \"$category->name\"…");
            });
    }
}

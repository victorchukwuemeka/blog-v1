<?php

use App\Models\Category;
use App\Jobs\GenerateCategoryPage;

use function Pest\Laravel\artisan;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Date;
use App\Console\Commands\RefreshCategoryPagesCommand;

it('queues generation jobs only for categories modified a week ago or earlier', function () {
    Bus::fake();

    Date::setTestNow(now());

    // Should be queued: modified more than a week ago.
    $stale = Category::factory()->create(['modified_at' => now()->subWeeks(2)]);

    // Should NOT be queued: never modified.
    $neverModified = Category::factory()->create(['modified_at' => null]);

    // Should NOT be queued: modified within the last week.
    $fresh = Category::factory()->create(['modified_at' => now()->subDays(2)]);

    artisan(RefreshCategoryPagesCommand::class)
        ->assertSuccessful();

    Bus::assertDispatchedTimes(GenerateCategoryPage::class, 1);

    Bus::assertDispatched(
        GenerateCategoryPage::class,
        fn (GenerateCategoryPage $job) => $job->category->is($stale)
    );

    Bus::assertNotDispatched(
        GenerateCategoryPage::class,
        fn (GenerateCategoryPage $job) => $job->category->is($fresh)
    );

    Bus::assertNotDispatched(
        GenerateCategoryPage::class,
        fn (GenerateCategoryPage $job) => $job->category->is($neverModified)
    );
});

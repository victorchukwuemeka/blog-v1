<?php

use App\Models\Post;
use App\Models\Report;
use App\Models\Revision;

use function Pest\Laravel\assertDatabaseCount;

it('deletes revisions before deleting a report', function () {
    $report = Report::factory()->for(Post::factory())->create();

    Revision::factory(3)
        ->for($report)
        ->create();

    assertDatabaseCount(Revision::class, 3);

    $report->delete();

    assertDatabaseCount(Revision::class, 0);
});

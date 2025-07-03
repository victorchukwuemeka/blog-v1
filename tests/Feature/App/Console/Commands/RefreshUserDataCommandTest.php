<?php

use App\Models\User;
use App\Jobs\RefreshUserData;

use function Pest\Laravel\artisan;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Date;
use App\Console\Commands\RefreshUserDataCommand;

it('queues a refresh job for a specific user when an id is provided', function () {
    Bus::fake();

    Date::setTestNow(now());

    $user = User::factory()->create();

    artisan(RefreshUserDataCommand::class, ['id' => $user->id])
        ->assertSuccessful();

    Bus::assertDispatched(
        RefreshUserData::class,
        fn (RefreshUserData $job) => $job->user->is($user)
    );

    Bus::assertDispatchedTimes(RefreshUserData::class, 1);
});

it('queues refresh jobs for every user whose data is missing or older than a day', function () {
    Bus::fake();

    Date::setTestNow(now());

    // Users that should be queued.
    $neverRefreshedUser = User::factory()->create(['refreshed_at' => null]);
    $userWhoNeedsARefresh = User::factory()->create(['refreshed_at' => now()->subDays(2)]);

    // User that should NOT be queued (last refresh was less than a day ago).
    $freshUser = User::factory()->create(['refreshed_at' => now()->subHours(1)]);

    artisan(RefreshUserDataCommand::class)
        ->assertSuccessful();

    Bus::assertDispatchedTimes(RefreshUserData::class, 2);

    Bus::assertDispatched(
        RefreshUserData::class,
        fn (RefreshUserData $job) => $job->user->is($neverRefreshedUser)
    );

    Bus::assertDispatched(
        RefreshUserData::class,
        fn (RefreshUserData $job) => $job->user->is($userWhoNeedsARefresh)
    );

    Bus::assertNotDispatched(
        RefreshUserData::class,
        fn (RefreshUserData $job) => $job->user->is($freshUser)
    );
});

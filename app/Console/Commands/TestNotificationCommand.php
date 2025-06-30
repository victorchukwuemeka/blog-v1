<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\Welcome;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:test-notification',
    description: 'Test the notification system.'
)]
class TestNotificationCommand extends Command
{
    protected $signature = 'app:test-notification';

    public function handle() : void
    {
        User::findOrFail(1)->notify(new Welcome);
    }
}

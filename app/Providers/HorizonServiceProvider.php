<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    protected function gate() : void
    {
        Gate::define('viewHorizon', function (User $user) {
            if (request()->bearerToken() && request()->bearerToken() === config('services.horizon.token')) {
                return true;
            }

            return in_array($user->email, [
                'benjamincrozat@me.com',
            ]);
        });
    }
}

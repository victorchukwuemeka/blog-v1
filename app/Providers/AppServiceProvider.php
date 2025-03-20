<?php

namespace App\Providers;

use App\Models\Metric;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot() : void
    {
        // Not necessary, but why not?
        Date::use(CarbonImmutable::class);

        // This one helps you catch lots of issues. Check
        // the source code to see what it does.
        Model::shouldBeStrict(! app()->isProduction());

        // Be careful with unguarded models! But
        // this trick removes a lot of friction.
        Model::unguard();

        View::composer('*', fn (\Illuminate\View\View $view) => $view->with(
            'visitors',
            $this->visitors ??= Metric::query()->where('key', 'visitors')->value('value') ?? 0
        ));
    }
}

<?php

namespace App\Http\Controllers\Advertising;

use App\Models\Metric;
use Illuminate\View\View;
use Illuminate\Support\Number;
use App\Http\Controllers\Controller;

class AdvertiseController extends Controller
{
    public function __invoke() : View
    {
        return view('advertise', [
            'views' => Number::format(
                Metric::query()->where('key', 'views')->value('value')
            ),
            'sessions' => Number::format(
                Metric::query()->where('key', 'sessions')->value('value')
            ),
            'desktop' => Number::format(
                Metric::query()->where('key', 'platform_desktop')->value('value'), 0
            ),
        ]);
    }
}

<?php

namespace App\Actions;

use Illuminate\Container\Attributes\Config;

class SelectProxy
{
    public function __construct(
        #[Config('proxies')]
        protected readonly array $proxies
    ) {}

    public function select(?string $country = null) : string
    {
        if ($country) {
            $proxy = $this->proxies[$country];

            $port = collect(range($proxy['port_range'], $proxy['port_range'] + 100))->random();

            return "{$proxy['hostname']}:$port";
        }

        // If no country is provided, use the global proxy with a random port.

        $port = collect(range(10000, 10100))->random();

        return "gate.smartproxy.com:$port";
    }
}

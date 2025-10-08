<?php

namespace App\Feed;

use Carbon\CarbonImmutable;

class FeedItem
{
    public function __construct(
        public string $url,
        public ?CarbonImmutable $publishedAt,
        public string $title,
    ) {}
}

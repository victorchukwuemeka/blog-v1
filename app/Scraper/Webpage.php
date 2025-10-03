<?php

namespace App\Scraper;

readonly class Webpage
{
    public function __construct(
        public string $url,
        public ?string $imageUrl,
        public string $title,
        public string $content,
    ) {}
}

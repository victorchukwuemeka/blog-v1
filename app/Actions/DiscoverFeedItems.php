<?php

namespace App\Actions;

use App\Feed\FeedItem;
use App\Feed\FeedReader;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Collection;

final class DiscoverFeedItems
{
    public function __construct(
        private ClientInterface $http,
        private FeedReader $reader,
    ) {}

    /**
     * @return Collection<FeedItem>
     */
    public function discover(string $feedUrl): Collection
    {
        $response = $this->http->request('GET', $feedUrl, [
            'headers' => [
                'Accept' => 'application/rss+xml, application/atom+xml, application/xml;q=0.9, text/xml;q=0.8, */*;q=0.5',
                'User-Agent' => 'benjamincrozat.com feed discovery bot',
            ],
            'timeout' => 10,
            'http_errors' => false,
        ]);

        $status = $response->getStatusCode();
        if ($status < 200 || $status >= 300) {
            return collect();
        }

        $xml = (string) $response->getBody();

        // Prefer self link as base URL when available.
        $baseUrl = $this->detectBaseUrl($xml, $feedUrl);

        $items = $this->reader->read($xml, $baseUrl);

        return collect($items);
    }

    private function detectBaseUrl(string $xml, string $fallback): string
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        @$dom->loadXML($xml, LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_NONET);
        $xp = new \DOMXPath($dom);
        $xp->registerNamespace('atom', 'http://www.w3.org/2005/Atom');
        $self = $xp->query('//atom:link[@rel="self"]/@href')?->item(0)?->nodeValue ?? '';

        return '' !== $self ? $self : $fallback;
    }
}

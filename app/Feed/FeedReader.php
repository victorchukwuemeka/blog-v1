<?php

namespace App\Feed;

use DOMXPath;
use DOMDocument;
use Carbon\CarbonImmutable;

final class FeedReader
{
    /**
     * @return array<FeedItem>
     */
    public function read(string $xml, string $baseUrl): array
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        @$dom->loadXML($xml, LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_NONET);

        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('atom', 'http://www.w3.org/2005/Atom');
        $xpath->registerNamespace('content', 'http://purl.org/rss/1.0/modules/content/');
        $xpath->registerNamespace('dc', 'http://purl.org/dc/elements/1.1/');

        $items = [];

        // Try Atom first.
        $entries = $xpath->query('//atom:entry');
        if ($entries && $entries->length > 0) {
            foreach ($entries as $entry) {
                $titleNode = $xpath->query('./atom:title', $entry)?->item(0);
                $title = trim(html_entity_decode($titleNode?->textContent ?? ''));

                $url = $this->firstAttr($xpath, './atom:link[@rel="alternate" and (@type="text/html" or not(@type))]/@href', $entry)
                    ?? $this->firstAttr($xpath, './atom:link/@href', $entry)
                    ?? trim($xpath->query('./atom:id', $entry)?->item(0)?->textContent ?? '');

                $published = trim($xpath->query('./atom:published', $entry)?->item(0)?->textContent ?? '')
                    ?: trim($xpath->query('./atom:updated', $entry)?->item(0)?->textContent ?? '');

                $url = $this->normalizeUrl($url, $baseUrl);
                $publishedAt = $this->parseDate($published);

                if (null !== $url && '' !== $title) {
                    $items[] = new FeedItem($url, $publishedAt, $title);
                }
            }

            return $items;
        }

        // Fallback to RSS 2.0.
        $rssItems = $xpath->query('//channel/item');
        if ($rssItems) {
            foreach ($rssItems as $node) {
                $titleNode = $xpath->query('./title', $node)?->item(0);
                $title = trim(html_entity_decode($titleNode?->textContent ?? ''));

                $link = trim($xpath->query('./link', $node)?->item(0)?->textContent ?? '');
                if ('' === $link) {
                    $guid = $xpath->query('./guid[not(@isPermaLink="false")]', $node)?->item(0)?->textContent ?? '';
                    $link = trim($guid);
                }
                if ('' === $link) {
                    // Last resort: first anchor in content:encoded
                    $href = $this->firstAttr($xpath, './content:encoded//a[1]/@href', $node);
                    $link = $href ? trim($href) : '';
                }

                $pubDate = trim($xpath->query('./pubDate', $node)?->item(0)?->textContent ?? '')
                    ?: trim($xpath->query('./dc:date', $node)?->item(0)?->textContent ?? '');

                $url = $this->normalizeUrl($link, $baseUrl);
                $publishedAt = $this->parseDate($pubDate);

                if (null !== $url && '' !== $title) {
                    $items[] = new FeedItem($url, $publishedAt, $title);
                }
            }
        }

        return $items;
    }

    private function firstAttr(DOMXPath $xpath, string $query, \DOMNode $ctx): ?string
    {
        $n = $xpath->query($query, $ctx);
        if (! $n || 0 === $n->length) {
            return null;
        }
        $val = trim($n->item(0)?->nodeValue ?? '');

        return '' !== $val ? $val : null;
    }

    private function parseDate(?string $value): ?CarbonImmutable
    {
        $value = trim((string) ($value ?? ''));
        if ('' === $value) {
            return null;
        }
        try {
            return new CarbonImmutable($value);
        } catch (\Throwable) {
            try {
                return CarbonImmutable::parse($value);
            } catch (\Throwable) {
                return null;
            }
        }
    }

    private function normalizeUrl(?string $url, string $baseUrl): ?string
    {
        $url = trim((string) ($url ?? ''));
        if ('' === $url) {
            return null;
        }

        // Resolve relative URLs.
        if (! preg_match('~^https?://~i', $url)) {
            $url = $this->resolveRelativeUrl($url, $baseUrl);
        }

        // Strip fragment.
        $parts = parse_url($url);
        if (false === $parts) {
            return null;
        }
        unset($parts['fragment']);

        // Optionally strip common tracking params like utm_*.
        if (isset($parts['query'])) {
            parse_str($parts['query'], $q);
            $q = array_filter($q, fn($k) => ! preg_match('/^utm_/i', (string) $k), ARRAY_FILTER_USE_KEY);
            $parts['query'] = http_build_query($q);
            if ('' === $parts['query']) {
                unset($parts['query']);
            }
        }

        $rebuilt = $this->buildUrl($parts);

        return '' !== $rebuilt ? $rebuilt : null;
    }

    private function resolveRelativeUrl(string $relative, string $base): string
    {
        // Very small resolver; good enough for typical feed links.
        $bp = parse_url($base) ?: [];
        if (str_starts_with($relative, '//')) {
            return ($bp['scheme'] ?? 'https') . ':' . $relative;
        }
        if (str_starts_with($relative, '/')) {
            return ($bp['scheme'] ?? 'https') . '://' . ($bp['host'] ?? '') . $relative;
        }
        $path = $bp['path'] ?? '/';
        $path = preg_replace('~[^/]+$~', '', $path) ?: '/';

        return ($bp['scheme'] ?? 'https') . '://' . ($bp['host'] ?? '') . $path . $relative;
    }

    private function buildUrl(array $parts): string
    {
        $scheme = isset($parts['scheme']) ? $parts['scheme'] . '://' : '';
        $host = $parts['host'] ?? '';
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';
        $user = $parts['user'] ?? '';
        $pass = isset($parts['pass']) ? ':' . $parts['pass'] : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = $parts['path'] ?? '';
        $query = isset($parts['query']) ? '?' . $parts['query'] : '';

        return "$scheme$user$pass$host$port$path$query";
    }
}

<?php

use GuzzleHttp\Client;
use App\Feed\FeedReader;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use App\Actions\DiscoverFeedItems;
use GuzzleHttp\Handler\MockHandler;

it('extracts url, publishedAt and title from LaraJobs RSS', function () {
    $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:wfw="http://wellformedweb.org/CommentAPI/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
     xmlns:job="https://larajobs.com"
>

    <channel>
        <title>LaraJobs</title>
        <atom:link href="https://larajobs.com" rel="self" type="application/rss+xml" />
        <link>https://larajobs.com</link>
        <description>Your Laravel Jobs source.</description>
        <lastBuildDate>Wed, 01 Oct 2025 01:49:18 +0000</lastBuildDate>
        <language>en-US</language>
        <sy:updatePeriod>hourly</sy:updatePeriod>
        <sy:updateFrequency>1</sy:updateFrequency>
        <generator>https://larajobs.com</generator>
                    <item>
                <title>Laravel Software Engineer</title>
                <link>https://larajobs.com/job/3720</link>
                <pubDate>Wed, 01 Oct 2025 01:49:18 +0000</pubDate>
                <dc:creator><![CDATA[Amalfi Jets]]></dc:creator>
                <category><![CDATA[Job]]></category>
                <job:location><![CDATA[Calabasas, CA, USA 91302]]></job:location>
                <job:job_type><![CDATA[FULL_TIME]]></job:job_type>
                <job:salary><![CDATA[$110,000 - $140,000 USD]]></job:salary>
                <job:company><![CDATA[Amalfi Jets]]></job:company>
                <job:company_logo>https://larajobs.com/logos/KhBmbUq26lcrU8sH7Xc15jMdQKg7IA5F9WKngEnA.png</job:company_logo>
                <job:tags><![CDATA[Backend,Laravel,PHP,React,TailwindCSS]]></job:tags>
                <guid isPermaLink="false">https://larajobs.com/job/3720</guid>
                <description><![CDATA[

                    ]]></description>
                <content:encoded><![CDATA[

                    ]]></content:encoded>
            </item>
    </channel>
</rss>
XML;

    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'application/rss+xml'], $xml),
    ]);
    $stack = HandlerStack::create($mock);
    $http = new Client(['handler' => $stack]);

    $reader = new FeedReader;
    $action = new DiscoverFeedItems($http, $reader);

    $items = $action->discover('https://larajobs.com/feed');

    expect($items)->toHaveCount(1);
    $item = $items->first();
    expect($item->url)->toBe('https://larajobs.com/job/3720');
    expect($item->title)->toBe('Laravel Software Engineer');
    expect($item->publishedAt)->not->toBeNull();
});

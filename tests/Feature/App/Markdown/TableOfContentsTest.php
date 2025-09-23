<?php

use App\Markdown\TableOfContents;

it('builds a flat table of contents from headings', function () {
    $markdown = <<<'MD'
# Foo

Some text

# Bar
MD;

    $toc = (new TableOfContents($markdown))->toArray();

    expect($toc)->toHaveCount(2)
        ->and($toc[0]['text'])->toBe('Foo')
        ->and($toc[0]['slug'])->toBe('foo')
        ->and($toc[0]['level'])->toBe(1)
        ->and($toc[0]['children'])->toBeArray()->toBeEmpty()
        ->and($toc[1]['text'])->toBe('Bar')
        ->and($toc[1]['slug'])->toBe('bar')
        ->and($toc[1]['level'])->toBe(1)
        ->and($toc[1]['children'])->toBeArray()->toBeEmpty();
});

it('builds a nested table of contents respecting heading levels', function () {
    $markdown = <<<'MD'
# A
## A1
### A1a
## A2
# B
MD;

    $toc = (new TableOfContents($markdown))->toArray();

    expect($toc)->toHaveCount(2)
        // A
        ->and($toc[0]['text'])->toBe('A')
        ->and($toc[0]['level'])->toBe(1)
        ->and($toc[0]['children'])->toHaveCount(2)
        // A1
        ->and($toc[0]['children'][0]['text'])->toBe('A1')
        ->and($toc[0]['children'][0]['level'])->toBe(2)
        ->and($toc[0]['children'][0]['children'])->toHaveCount(1)
        // A1a
        ->and($toc[0]['children'][0]['children'][0]['text'])->toBe('A1a')
        ->and($toc[0]['children'][0]['children'][0]['level'])->toBe(3)
        // A2
        ->and($toc[0]['children'][1]['text'])->toBe('A2')
        ->and($toc[0]['children'][1]['level'])->toBe(2)
        // B
        ->and($toc[1]['text'])->toBe('B')
        ->and($toc[1]['level'])->toBe(1)
        ->and($toc[1]['children'])->toBeArray()->toBeEmpty();
});

it('ignores headings inside fenced code blocks', function () {
    $markdown = <<<'MD'
# Outside

```
# Inside
## Also inside
```

# Outside 2
MD;

    $toc = (new TableOfContents($markdown))->toArray();

    expect($toc)->toHaveCount(2)
        ->and($toc[0]['text'])->toBe('Outside')
        ->and($toc[1]['text'])->toBe('Outside 2');
});

it('parses markdown inside headings and generates proper slugs', function () {
    $markdown = <<<'MD'
# [*Hello*, World!](https://example.com)
MD;

    $toc = (new TableOfContents($markdown))->toArray();

    expect($toc)->toHaveCount(1)
        ->and($toc[0]['text'])->toBe('Hello, World!')
        ->and($toc[0]['slug'])->toBe('hello-world');
});

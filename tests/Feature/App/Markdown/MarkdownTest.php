<?php

use App\Markdown\Markdown;

it('adds IDs to headings', function () {
    $markdown = '# Foo';

    $html = Markdown::parse($markdown);

    expect($html)->toContain('id="foo"');
});

it('disallows some HTML', function (string $input) {
    $html = Markdown::parse($input);

    expect($html)->toContain('&lt;');
})->with([
    '<noembed>foo</noembed>',
    '<noframes>foo</noframes>',
    '<plaintext>foo</plaintext>',
    '<script>alert("foo")</script>',
    '<style>body { color: red }</style>',
    '<textarea>foo</textarea>',
    '<title>foo</title>',
    '<xmp>foo</xmp>',
]);

it('opens external links in new windows', function () {
    $markdown = 'https://example.com';

    $html = Markdown::parse($markdown);

    expect($html)->toContain('target="_blank"');
});

it('converts quotes to smart quotes', function () {
    $markdown = '"';

    $html = Markdown::parse($markdown);

    expect($html)->toContain('“');
});

it('renders code blocks with syntax highlighting', function () {
    $markdown = <<<'MD'
```php
$user = User::find(1);
```
MD;

    $html = Markdown::parse($markdown);

    expect($html)->toContain('<pre data-lang="php" class="notranslate">');
});

it('renders inline code', function () {
    $markdown = '`some inline code`';

    $html = Markdown::parse($markdown);

    expect($html)->toContain('<code>some inline code</code>');
});

it('gets text content from nested nodes', function () {
    $markdown = '## [*Foo*](https://example.com)';

    $html = Markdown::parse($markdown);

    expect($html)->toContain(
        '<h2',
        'href="https://example.com"',
        'Foo',
    );
});

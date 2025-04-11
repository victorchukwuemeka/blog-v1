<?php

namespace App\Actions\Posts;

use App\Str;
use Illuminate\Support\Facades\Date;
use Symfony\Component\Finder\SplFileInfo;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class ParseMarkdownFile
{
    /**
     * @return array{
     *     image: ?string,
     *     title: string,
     *     slug: string,
     *     content: string,
     *     description: string,
     *     published_at: \Illuminate\Support\Carbon,
     *     modified_at: \Illuminate\Support\Carbon,
     *     canonical: string,
     * }
     */
    public function parse(SplFileInfo|string $file) : array
    {
        if (is_string($file)) {
            $file = new SplFileInfo($file, '', '');
        }

        $post = YamlFrontMatter::parse($file->getContents());

        return [
            'image' => $post->matter('Image'),
            'title' => $post->matter('Title'),
            'slug' => Str::slug(basename($file->getFilename(), '.md')),
            'content' => $post->body(),
            'description' => $post->matter('Description'),
            'published_at' => $post->matter('Published at') ? Date::createFromTimestamp($post->matter('Published at')) : null,
            'modified_at' => $post->matter('Modified at') ? Date::createFromTimestamp($post->matter('Modified at')) : null,
            'canonical' => $post->matter('Canonical'),
        ];
    }
}

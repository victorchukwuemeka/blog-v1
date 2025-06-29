<x-app
    :canonical="$post->canonical_url"
    :description="$post->description"
    :image="$post->image_url"
    :title="$post->title"
>
    <article class="mt-0 md:mt-8">
        <div class="container break-all lg:max-w-(--breakpoint-md)">
            @if ($post->hasImage())
                <img src="{{ $post->image_url }}" alt="{{ $post->title  }}" class="object-cover w-full rounded-xl ring-1 shadow-xl ring-black/5 aspect-video" />
            @endif
        </div>

        <h1 class="container mt-12 font-medium tracking-tight text-center text-black md:mt-16 text-balance text-3xl/none sm:text-4xl/none md:text-5xl/none lg:text-6xl/none">
            {{ $post->title }}
        </h1>

        <div class="container mt-12 md:mt-16 lg:max-w-(--breakpoint-md)">
            <div class="grid grid-cols-2 gap-4 text-sm leading-tight md:grid-cols-4">
                <div class="flex-1 p-3 text-center bg-gray-50 rounded-lg">
                    <x-heroicon-o-calendar class="mx-auto mb-2 opacity-75 size-6" />
                    {{ $post->modified_at ? 'Modified' : 'Published' }}<br />
                    {{ ($post->modified_at ?? $post->published_at)->isoFormat('ll') }}
                </div>

                <a
                    wire:navigate
                    href="{{ route('authors.show', $post->user) }}"
                >
                    <div class="flex-1 p-3 text-center bg-gray-50 rounded-lg transition-colors hover:bg-blue-50 group-hover:text-blue-900">
                        <x-heroicon-o-user class="mx-auto mb-2 opacity-75 size-6" />
                        Written by<br />
                        {{ $post->user->name }}
                    </div>
                </a>

                <a href="#comments" class="group">
                    <div @class([
                        'flex-1 p-3 text-center transition-colors rounded-lg bg-gray-50 hover:bg-blue-50 group-hover:text-blue-900',
                        'text-blue-600' => $post->comments_count > 0,
                    ])>
                        <x-heroicon-o-chat-bubble-oval-left-ellipsis class="mx-auto mb-2 opacity-75 size-6" />
                        {{ $post->comments_count }}<br />
                        {{ trans_choice('comment|comments', $post->comments_count) }}
                    </div>
                </a>

                <div class="flex-1 p-3 text-center bg-gray-50 rounded-lg">
                    <x-heroicon-o-clock class="mx-auto mb-2 opacity-75 size-6" />
                    {{ $post->read_time }} minutes<br />
                    read
                </div>
            </div>

            @if (! empty($headings = extract_headings_from_markdown($post->content)))
                <x-table-of-contents
                    :$headings
                    class="mt-4 ml-0"
                />
            @endif

            <x-prose class="mt-24">
                {!! $post->formatted_content !!}

                @if (! empty($post->recommendedPosts))
                    <hr />

                    <p>Did you like this article? Then, keep learning:</p>

                    <ul>
                        @foreach ($post->recommendedPosts as $post)
                            <li>
                                <a href="{{ route('posts.show', $post) }}">
                                    {{ $post->reason }}&nbsp;→
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-prose>
        </div>
    </article>

    @if ($post->comments_count)
        <x-section
            id="comments"
            class="mt-24 lg:max-w-(--breakpoint-md)"
        >
            <livewire:comments :post-id="$post->id" />
        </x-section>
    @endif

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Article",
            "author": {
                "@type": "Person",
                "name": "{{ $post->user->name }}",
                "url": "{{ route('home') }}#about"
            },
            "headline": "{{ $post->title }}",
            "description": "{{ $post->description }}",
            "image": "{{ $post->image_url }}",
            "datePublished": "{{ $post->published_at->toIso8601String() }}",
            "dateModified": "{{ $post->modified_at?->toIso8601String() ?? $post->published_at->toIso8601String() }}"
        }
    </script>
</x-app>

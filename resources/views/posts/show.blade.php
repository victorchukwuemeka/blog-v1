<x-app
    :canonical="$post->canonical_url"
    :description="$post->description"
    :image="$post->image_url"
    :title="$post->title"
>
    <div class="flex items-center gap-8 px-4 mt-4 overflow-x-scroll md:px-8 snap-mandatory snap-x md:mt-8">
        @foreach (collect(config('merchants.books'))->shuffle() as $book)
            <x-book :$book class="flex-1 snap-start scroll-ml-4 md:scroll-ml-8 min-w-[150px]" />
        @endforeach
    </div>

    <x-breadcrumbs class="container mt-12 md:mt-16 xl:max-w-(--breakpoint-lg)">
        <x-breadcrumbs.item href="{{ route('posts.index') }}">
            Posts
        </x-breadcrumbs.item>

        <x-breadcrumbs.item class="line-clamp-1">
            {{ $post->title }}
        </x-breadcrumbs.item>
    </x-breadcrumbs>

    <article class="mt-12 md:mt-16">
        <div class="container break-all lg:max-w-(--breakpoint-md)">
            @if ($post->image_url)
                <img src="{{ $post->image_url }}" alt="{{ $post->title  }}" class="object-cover w-full shadow-xl ring-1 ring-black/5 rounded-xl aspect-video" />
            @endif
        </div>

        @if (! empty($post->categories))
            <div class="flex justify-center gap-2 mt-12 md:mt-16">
                @foreach ($post->categories as $category)
                    <div class="px-2 py-1 text-xs font-medium uppercase border border-gray-200 rounded-sm">
                        {{ $category->name }}
                    </div>
                @endforeach
            </div>
        @endif

        <h1 class="container mt-4 font-medium tracking-tight text-center text-black md:mt-8 text-balance text-3xl/none sm:text-4xl/none md:text-5xl/none lg:text-6xl/none">
            {{ $post->title }}
        </h1>

        <div class="container mt-12 md:mt-16 lg:max-w-(--breakpoint-md)">
            <div class="grid grid-cols-2 gap-4 text-sm leading-tight md:grid-cols-4">
                <div class="flex-1 p-3 text-center rounded-lg bg-gray-50">
                    <x-heroicon-o-calendar class="mx-auto mb-2 opacity-75 size-6" />
                    {{ $post->modified_at ? 'Modified' : 'Published' }}<br />
                    {{ ($post->modified_at ?? $post->published_at)->isoFormat('ll') }}
                </div>

                <div class="flex-1 p-3 text-center rounded-lg bg-gray-50">
                    <x-heroicon-o-user class="mx-auto mb-2 opacity-75 size-6" />
                    Written by<br />
                    {{ $post->user->name }}
                </div>

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

                <div class="flex-1 p-3 text-center rounded-lg bg-gray-50">
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

            <x-prose class="mt-12 md:mt-16">
                {!! Str::markdown($post->content) !!}
            </x-prose>
        </div>
    </article>

    <x-section
        id="comments"
        class="mt-12 md:mt-16 lg:max-w-(--breakpoint-md)"
    >
        <livewire:comments :post-id="$post->id" />
    </x-section>

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

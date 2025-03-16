<x-app
    :canonical="$post['canonical']"
    :description="$post['description']"
    :image="$post['image']"
    :title="$post['title']"
>
    <div class="flex items-center gap-8 px-4 mt-4 overflow-x-scroll md:px-8 snap-mandatory snap-x md:mt-8">
        @foreach (collect(config('merchants.books'))->shuffle() as $book)
            <x-book :$book class="flex-1 snap-start scroll-ml-4 md:scroll-ml-8 min-w-[150px]" />
        @endforeach
    </div>

    <x-breadcrumbs class="container mt-12 md:mt-16 xl:max-w-screen-lg">
        <x-breadcrumbs.item href="{{ route('posts.index') }}">
            Posts
        </x-breadcrumbs.item>

        <x-breadcrumbs.item class="line-clamp-1">
            {{ $post['title'] }}
        </x-breadcrumbs.item>
    </x-breadcrumbs>

    <article class="mt-12 md:mt-16">
        <div class="container break-all lg:max-w-screen-md">
            @if ($post['image'])
                <img src="{{ $post['image'] }}" alt="{{ $post['title']  }}" class="object-cover w-full shadow-xl ring-1 ring-black/5 rounded-xl aspect-video" />
            @endif
        </div>

        <h1 class="container mt-12 font-medium tracking-tight text-center text-black md:mt-16 text-balance text-3xl/none sm:text-4xl/none md:text-5xl/none lg:text-6xl/none">
            {{ $post['title'] }}
        </h1>

        <x-prose class="container mt-12 lg:max-w-screen-md md:mt-16">
            <div class="not-prose">
                <div class="grid grid-cols-2 gap-4 leading-tight md:grid-cols-4">
                    <div class="flex-1 p-3 text-center rounded-lg bg-gray-50">
                        <x-heroicon-o-calendar class="mx-auto mb-1 opacity-75 size-6" />
                        {{ $post['modified_at'] ? 'Modified' : 'Published' }}<br />
                        {{ ($post['modified_at'] ?? $post['published_at'])->isoFormat('ll') }}
                    </div>

                    <div class="flex-1 p-3 text-center rounded-lg bg-gray-50">
                        <x-heroicon-o-user class="mx-auto mb-1 opacity-75 size-6" />
                        Written by<br />
                        Benjamin Crozat
                    </div>

                    <a href="#comments" class="group">
                        <div @class([
                            'flex-1 p-3 text-center transition-colors rounded-lg bg-gray-50 hover:bg-blue-50 group-hover:text-blue-900',
                            'text-blue-600' => $post['comments_count'] > 0,
                        ])>
                            <x-heroicon-o-chat-bubble-oval-left-ellipsis class="mx-auto mb-1 opacity-75 size-6" />
                            {{ $post['comments_count'] }}<br />
                            {{ trans_choice('comment|comments', $post['comments_count']) }}
                        </div>
                    </a>

                    <div class="flex-1 p-3 text-center rounded-lg bg-gray-50">
                        <x-heroicon-o-clock class="mx-auto mb-1 opacity-75 size-6" />
                        {{ $readTime ?? 0 }} minutes<br />
                        read
                    </div>
                </div>

                <div class="px-4 py-6 mt-4 rounded-lg bg-gray-50">
                    <div class="text-sm font-bold tracking-widest text-center text-black uppercase">
                        Table of contents
                    </div>

                    <x-table-of-contents :headings="extract_headings_from_markdown($post['content'])" class="mt-4 ml-0" />
                </div>
            </div>

            {!! Str::markdown($post['content']) !!}
        </x-prose>
    </article>

    <div class="mt-12 md:mt-16">
        <livewire:comments :post-slug="$post['slug']" />
    </div>

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Article",
            "author": {
                "@type": "Person",
                "name": "Benjamin Crozat",
                "url": "{{ route('home') }}#about"
            },
            "headline": "{{ $post['title'] }}",
            "description": "{{ $post['description'] }}",
            "image": "{{ $post['image'] }}",
            "datePublished": "{{ $post['published_at']->toIso8601String() }}",
            "dateModified": "{{ $post['modified_at']?->toIso8601String() ?? $post['published_at']->toIso8601String() }}"
        }
    </script>
</x-app>

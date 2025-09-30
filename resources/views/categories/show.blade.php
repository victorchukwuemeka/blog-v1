<x-app
    :title="$category->title ?? 'The best articles about ' . $category->name . ' in ' . date('Y')"
    description="Level up in {{ date('Y') }} as a web developer with this collection of articles I wrote about {{ $category->name }}."
>
    <article>
        @if ($category->content && $posts->currentPage() === 1)
            <p class="text-sm font-normal tracking-widest text-center uppercase md:text-base">
                {{ trans_choice(':count minute|:count minutes', $category->read_time) }}
                read
            </p>

            <h1 class="mt-2 font-medium tracking-tight text-center text-black text-balance text-3xl/none sm:text-4xl/none lg:text-5xl/none">
                {{ $category->title }}
            </h1>
            <x-prose class="mb-16 container mt-8 max-w-(--breakpoint-md)!">
                <div class="not-prose">
                    {!! $category->toTableOfContents() !!}
                </div>

                {!! Markdown::parse($category->content) !!}
            </x-prose>
        @endif

        @if ($posts->isNotEmpty())
            <x-heading
                tag="h2"
                id="all-articles-about-{{ strtolower($category->name) }}"
            >
                All articles about {{ $category->name }}
            </x-heading>

            <div class="container">
                <x-posts-grid :$posts class="mt-10" />

                <x-pagination
                    :paginator="$posts"
                    class="mt-16"
                />
            </div>
        @endif
    </article>

    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@type": "Article",
            "author": {
                "@type": "Person",
                "name": "Benjamin Crozat",
                "url": "{{ route('home') }}#about"
            },
            "headline": "{{ $category->title }}",
            "datePublished": "{{ $category->created_at->toIso8601String() }}",
            @if ($category->updated_at)
            "dateModified": "{{ $category->updated_at->toIso8601String() }}"
            @endif
        }
    </script>
</x-app>

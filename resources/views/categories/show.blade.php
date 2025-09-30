<x-app
    :title="$category->title ?? 'The best articles about ' . $category->name . ' in ' . date('Y')"
    description="Level up in {{ date('Y') }} as a web developer with this collection of articles I wrote about {{ $category->name }}."
>
    <article>
        @if ($category->content && $posts->currentPage() === 1)
            <x-prose class="container mb-16 max-w-(--breakpoint-md)!">
                <h1>{{ $category->title }}</h1>

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
</x-app>

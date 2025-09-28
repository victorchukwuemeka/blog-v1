<x-app
    :title="$category->title ?? 'The best articles about ' . $category->name . ' in ' . date('Y')"
    description="Level up in {{ date('Y') }} as a web developer with this collection of articles I wrote about {{ $category->name }}."
>
    <x-section
        :big-title="$posts->currentPage() === 1"
    >
        <x-slot:title>
            @if ($category->content)
                {{ $category->title }}
            @else
                @if ($posts->currentPage() > 1)
                    Page {{ $posts->currentPage() }}
                @else
                    {{ $category->name }}
                @endif
            @endif
        </x-slot:title>

        @if ($category->content)
            <x-prose class="mx-auto max-w-(--breakpoint-md)!">
                <h1>{{ $category->title }}</h1>

                {!! Markdown::parse($category->content) !!}
            </x-prose>
        @endif

        @if ($posts->isNotEmpty())
            @if ($category->content)
                <x-heading class="mt-16">
                    All articles about {{ $category->name }}
                </x-heading>
            @endif

            <ul class="grid gap-10 gap-y-16 mt-10 xl:gap-x-16 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($posts as $post)
                    <li>
                        <x-post :$post />
                    </li>
                @endforeach
            </ul>
        @endif

        <x-pagination
            :paginator="$posts"
            class="mt-16"
        />
    </x-section>
</x-app>

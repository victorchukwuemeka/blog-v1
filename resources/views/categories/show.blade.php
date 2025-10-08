<x-app
    title="The best articles about {{ $category->name }} in {{ date('Y') }}"
    description="Level up in {{ date('Y') }} as a web developer with this collection of articles I wrote about {{ $category->name }}."
>
    <article class="container">
        @if ($category->content && $posts->currentPage() === 1)
            <x-heading>
                Articles in the {{ $category->name }} category
            </x-heading>
        @else
            <x-heading>
                Page {{ $posts->currentPage() }}
            </x-heading>
        @endif

        <x-posts-grid :$posts class="mt-10" />

        <x-pagination
            :paginator="$posts"
            class="mt-16"
        />
    </article>
</x-app>

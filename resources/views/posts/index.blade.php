<x-app title="The best articles about web development in {{ date('Y') }}">
    <x-section
        :title="$posts->currentPage() > 1
            ? 'Page ' . $posts->currentPage()
            : 'The latest articles I wrote'"
        :big-title="$posts->currentPage() === 1"
        class="mt-0 md:mt-8"
    >
        @if ($posts->isNotEmpty())
            <div class="grid gap-10 mt-8 md:mt-16 gap-y-16 xl:gap-x-16 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($posts as $post)
                    <x-post :$post />
                @endforeach
            </div>
        @endif

        @if ($posts->hasPages())
            <div class="mt-16">
                {{ $posts->links() }}
            </div>
        @endif
    </x-section>
</x-app>

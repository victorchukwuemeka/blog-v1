<x-app title="The best articles about web development in {{ date('Y') }}">
    <x-section
        :title="$posts->currentPage() > 1
            ? 'Page ' . $posts->currentPage()
            : 'Latest'"
        :big-title="$posts->currentPage() === 1"
    >
        @if ($posts->isNotEmpty())
            <ul class="grid gap-10 gap-y-16 xl:gap-x-16 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($posts as $post)
                    <li>
                        <x-post :$post />
                    </li>
                @endforeach
            </ul>
        @endif

        @if ($posts->hasPages())
            <div class="mt-16">
                {{ $posts->links() }}
            </div>
        @endif
    </x-section>
</x-app>

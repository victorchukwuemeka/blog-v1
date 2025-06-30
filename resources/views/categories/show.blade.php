<x-app
    title="The best articles about {{ $category->name }} in {{ date('Y') }}"
    description="Level up in {{ date('Y') }} as a web developer with this collection of articles I wrote about {{ $category->name }}."
>
    <x-section
        :title="$posts->currentPage() > 1
            ? 'Page ' . $posts->currentPage()
            : 'The best articles about<br /> ' . $category->name . ' in ' . date('Y')"
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

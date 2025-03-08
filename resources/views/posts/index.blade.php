<x-app>
    <x-section title="Latest posts">
        @if ($posts->isNotEmpty())
            <ul class="grid gap-16 mt-8 md:grid-cols-2 lg:grid-cols-3">
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

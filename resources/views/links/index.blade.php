<x-app
    title="The best community-written articles about web development in {{ date('Y') }}"
    description="A collection of content created and shared by other web developers."
>
    <x-section class="mt-0 md:mt-8">
        @if ($links->isNotEmpty())
            <ul class="grid gap-10 gap-y-16 xl:gap-x-16 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($links as $link)
                    <li>
                        <x-link :$link />
                    </li>
                @endforeach
            </ul>
        @endif

        @if ($links->hasPages())
            <div class="mt-16">
                {{ $links->links() }}
            </div>
        @endif
    </x-section>
</x-app>

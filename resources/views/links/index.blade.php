<x-app
    title="The best community-written articles about web development in {{ date('Y') }}"
    description="A collection of content created and shared by other web developers."
>
    @if ($links->currentPage() === 1)
        <div class="container mt-4 text-center md:mt-8">
            <h1 class="font-medium text-black text-4xl/none md:text-5xl lg:text-7xl text-balance">
                <span class="text-blue-600">Keep learning</span> with the community
            </h1>

            <div class="mt-5 text-balance text-black/75 text-lg/tight sm:text-xl/tight md:text-2xl/tight md:mt-8">
                Find tons of resources written and shared by <span class="font-medium">{{ $distinctUsersCount }} web developers</span>.
            </div>

            <div class="flex items-center justify-center mt-4 md:mt-6">
                @foreach ($distinctUserAvatars as $avatar)
                    <div class="-ml-2 overflow-hidden bg-white rounded-full">
                        <img src="{{ $avatar }}" class="size-8 md:size-10" />
                    </div>
                @endforeach
            </div>

            <div class="flex items-center justify-center gap-2 mt-8 text-center md:mt-12">
                <x-btn href="#links">
                    Browse
                </x-btn>

                <x-btn
                    primary
                    :wire:navigate="auth()->check()"
                    href="{{ route('links.create') }}"
                >
                    Submit a link
                </x-btn>
            </div>
        </div>
    @endif

    <x-section :title="$links->currentPage() > 1
        ? 'Page ' . $links->currentPage()
        : 'Latest Links'" id="links" @class([
        'mt-16 md:mt-24' => $links->currentPage() === 1,
    ])>
        @if ($links->isNotEmpty())
            <ul class="grid gap-10 mt-8 gap-y-16 xl:gap-x-16 md:grid-cols-2 xl:grid-cols-3">
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

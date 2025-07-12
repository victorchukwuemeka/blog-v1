<x-app>
    <div class="container text-center">
        <div class="font-medium tracking-tight text-black text-4xl/none md:text-5xl lg:text-7xl text-balance">
            <span class="text-blue-600">{{ Number::format($visitors) }}</span> monthly visitors read my blog
        </div>

        <div class="mt-5 tracking-tight text-black/75 text-lg/tight sm:text-xl/tight md:text-2xl/tight md:mt-8">
            No matter how senior,<br />
            I have something for you. Ready?
        </div>

        <div class="flex gap-2 justify-center items-center mt-7 text-center md:mt-11">
            <x-btn
                size="md"
                wire:navigate
                href="{{ route('authors.show', 'benjamin-crozat') }}"
            >
                Who the F are you?
            </x-btn>

            <x-btn
                primary
                size="md"
                href="#popular"
            >
                Start reading
            </x-btn>
        </div>
    </div>

    @if ($popular->isNotEmpty())
        <x-section title="Popular posts" id="popular" class="mt-24 md:mt-32">
            <ul class="grid gap-10 gap-y-16 xl:gap-x-16 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($popular as $post)
                    <li>
                        <x-post :$post />
                    </li>
                @endforeach
            </ul>

            <div class="mt-16 text-center">
                <x-btn
                    primary
                    wire:navigate
                    href="{{ route('posts.index') }}"
                >
                    Browse all articles
                </x-btn>
            </div>
        </x-section>
    @endif

    <x-section title="Latest links" id="links" class="mt-24 md:mt-32">
        @if ($links->isNotEmpty())
            <ul class="grid gap-10 gap-y-16 xl:gap-x-16 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($links as $link)
                    <li>
                        <x-link :$link />
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="mt-16 text-center">
            <x-btn
                primary
                wire:navigate
                href="{{ route('links.index') }}"
            >
                Browse all links
            </x-btn>
        </div>
    </x-section>

    <x-section title="Latest posts" id="latest" class="mt-24 md:mt-32">
        @if ($latest->isNotEmpty())
            <ul class="grid gap-10 gap-y-16 xl:gap-x-16 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($latest as $post)
                    <li>
                        <x-post :$post />
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="mt-16 text-center">
            <x-btn
                primary
                wire:navigate
                href="{{ route('posts.index') }}"
            >
                Browse all articles
            </x-btn>
        </div>
    </x-section>

    @if ($aboutUser->biography)
        <x-section title="About {{ $aboutUser->name }}" id="about" class="mt-24 lg:max-w-(--breakpoint-md) md:mt-32">
            <x-prose>
                <img
                    src="{{ $aboutUser->avatar }}"
                    alt="{{ $aboutUser->name }}"
                    class="float-right mt-4 ml-4 rounded-full! size-20 sm:size-28 md:size-32"
                />

                {!! Str::markdown($aboutUser->biography) !!}
            </x-prose>
        </x-section>
    @endif
</x-app>

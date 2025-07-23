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
            <x-posts-grid :posts="$popular" />

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

    <x-section
        title="Great deals for developers"
        class="mt-24 md:mt-32"
    >
        <div class="grid gap-8 mt-8 lg:grid-cols-2">
            <x-deals.tower />
            <x-deals.fathom-analytics />
            <x-deals.cloudways />
            <x-deals.mailcoach />
            <x-deals.wincher />
            <x-deals.uptimia />
        </div>
    </x-section>

    <x-section title="Latest posts" id="latest" class="mt-24 md:mt-32">
        @if ($latest->isNotEmpty())
            <x-posts-grid :posts="$latest" />
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

    <x-section title="Latest links" id="links" class="mt-24 md:mt-32">
        @if ($links->isNotEmpty())
            <x-links-grid :$links />
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

    @if ($aboutUser)
        <x-section title="About {{ $aboutUser->name }}" id="about" class="mt-24 lg:max-w-(--breakpoint-md) md:mt-32">
            <x-prose>
                <img
                    loading="lazy"
                    src="{{ $aboutUser->avatar }}"
                    alt="{{ $aboutUser->name }}"
                    class="float-right mt-4 ml-4 rounded-full! size-20 sm:size-28 md:size-32"
                />

                {!! Str::markdown($aboutUser->biography) !!}
            </x-prose>
        </x-section>
    @endif
</x-app>

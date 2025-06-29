<x-app>
    <div class="container mt-8 text-center">
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
                href="#about"
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
            <ul class="grid gap-10 gap-y-16 mt-8 xl:gap-x-16 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($popular as $post)
                    <li>
                        <x-post :$post />
                    </li>
                @endforeach
            </ul>
        </x-section>
    @endif

    <x-section title="Latest posts" id="latest" class="mt-24 md:mt-32">
        @if ($latest->isNotEmpty())
            <ul class="grid gap-10 gap-y-16 mt-8 xl:gap-x-16 md:grid-cols-2 xl:grid-cols-3">
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

    @if ($about)
        <x-section title="About Benjamin Crozat" id="about" class="mt-24 lg:max-w-(--breakpoint-md) md:mt-32">
            <x-prose class="mt-8">
                <img
                    src="https://www.gravatar.com/avatar/d58b99650fe5d74abeb9d9dad5da55ad?s=256"
                    alt="Benjamin Crozat"
                    class="float-right mt-4 ml-4 rounded-full! size-28 md:size-32"
                />

                {!! Str::markdown($about) !!}
            </x-prose>
        </x-section>
    @endif
</x-app>

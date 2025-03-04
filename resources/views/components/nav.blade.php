<nav {{ $attributes->class('flex items-center gap-8 font-normal text-xs') }}>
    <a
        wire:navigate
        href="{{ route('home') }}"
        class="flex items-center gap-3"
    >
        <div class="grid bg-black place-items-center size-9 md:size-10 rounded-[.65rem] md:rounded-xl">
            <x-heroicon-s-bold class="text-white size-5 md:size-6" />
        </div>

        <div class="font-medium tracking-tighter sr-only sm:not-sr-only sm:text-lg md:text-xl">
            benjamincrozat.com
        </div>
    </a>

    <div class="flex-grow"></div>

    <a
        wire:navigate
        href="{{ route('home') }}"
        @class([
            'transition-colors hover:text-blue-600',
            'text-blue-600' => request()->routeIs('home'),
        ])"
    >
        @if (request()->routeIs('home'))
            <x-heroicon-s-home class="mx-auto size-7" />
        @else
            <x-heroicon-o-home class="mx-auto size-7" />
        @endif

        Home
    </a>

    <a
        wire:navigate
        href="{{ route('posts.index') }}"
        @class([
            'transition-colors hover:text-blue-600',
            'text-blue-600' => request()->routeIs('posts.index'),
        ])"
    >
        @if (request()->routeIs('posts.index'))
            <x-heroicon-s-fire class="mx-auto size-7" />
        @else
            <x-heroicon-o-fire class="mx-auto size-7" />
        @endif

        Latest
    </a>

    <a
        href="{{ route('home') }}#about"
        class="transition-colors hover:text-blue-600"
    >
        <x-heroicon-o-user class="mx-auto size-7" />
        About
    </a>

    <div x-data="{ open: false }">
        <button @click="open = !open">
            <x-heroicon-o-ellipsis-horizontal class="mx-auto size-7" />
            More
        </button>

        <div
            class="z-10 py-2 text-base bg-white rounded-lg shadow-lg ring-1 ring-black/10"
            x-anchor.bottom="$el.previousElementSibling"
            x-show="open"
            x-transition
            @click.away="open = false"
        >
            <a href="https://github.com/benjamincrozat/blog-v5" target="_blank" class="block px-4 py-2 font-medium transition-colors hover:bg-blue-600 hover:text-white">
                Fork the source code
            </a>

            <div class="h-px my-2 bg-black/10"></div>

            <div class="px-4 py-2 text-xs uppercase">Follow me</div>

            <a href="https://github.com/benjamincrozat" target="_blank" class="block px-4 py-2 font-medium transition-colors hover:bg-blue-600 hover:text-white">
                GitHub
            </a>

            <a href="https://www.linkedin.com/in/benjamincrozat" target="_blank" class="block px-4 py-2 font-medium transition-colors hover:bg-blue-600 hover:text-white">
                LinkedIn
            </a>

            <a href="https://x.com/benjamincrozat" target="_blank" class="block px-4 py-2 font-medium transition-colors hover:bg-blue-600 hover:text-white">
                X
            </a>
        </div>
    </div>
</nav>

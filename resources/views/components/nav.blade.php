<nav {{ $attributes->class('flex items-center gap-6 md:gap-8 font-normal text-xs') }}>
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
            <x-heroicon-s-home class="mx-auto size-6 md:size-7" />
        @else
            <x-heroicon-o-home class="mx-auto size-6 md:size-7" />
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
            <x-heroicon-s-fire class="mx-auto size-6 md:size-7" />
        @else
            <x-heroicon-o-fire class="mx-auto size-6 md:size-7" />
        @endif

        Latest
    </a>

    @auth
        <div x-data="{ open: false }">
            <button @click="open = !open">
                <x-heroicon-o-user class="mx-auto size-6 md:size-7" />
                Account
            </button>

            <div
                class="z-10 py-2 text-base bg-white rounded-lg shadow-lg ring-1 ring-black/10 min-w-[240px]"
                x-anchor.bottom="$el.previousElementSibling"
                x-cloak
                x-show="open"
                x-transition
                @click.away="open = false"
            >
                <div class="flex items-center gap-3 px-4 py-2">
                    <img
                        src="{{ auth()->user()->github_data['avatar'] }}"
                        alt="{{ auth()->user()->name }}'s GitHub avatar"
                        class="rounded-full size-6 md:size-8"
                    />

                    {{ auth()->user()->name }}
                </div>

                <div class="h-px my-2 bg-black/10"></div>

                <a href="https://github.com/settings" target="_blank" class="flex items-center gap-2 px-4 py-2 transition-colors hover:bg-blue-600 hover:text-white">
                    <x-heroicon-o-arrow-top-right-on-square class="size-4" />
                    Manage on GitHub
                </a>

                <button
                    form="logout-form"
                    class="flex items-center w-full gap-2 px-4 py-2 transition-colors hover:bg-red-600 hover:text-white"
                >
                    <x-heroicon-o-arrow-right-end-on-rectangle class="size-4" />
                    Log out
                </button>

                <form method="POST" action="{{ route('auth.logout') }}" id="logout-form" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    @else
        <a
            href="{{ route('auth.redirect') }}"
            class="transition-colors hover:text-blue-600"
        >
            <x-iconoir-github class="mx-auto size-6 md:size-7" />
            Sign in
        </a>
    @endauth

    <div x-data="{ open: false }">
        <button @click="open = !open">
            <x-heroicon-o-ellipsis-horizontal
                class="mx-auto transition-transform size-6 md:size-7"
                x-bind:class="open ? 'rotate-90' : 'rotate-0'"
            />

            More
        </button>

        <div
            class="z-10 py-2 text-base bg-white rounded-lg shadow-lg ring-1 ring-black/10 min-w-[240px]"
            x-anchor.bottom="$el.previousElementSibling"
            x-cloak
            x-show="open"
            x-transition
            @click.away="open = false"
        >
            <a href="{{ route('home') }}#about" class="flex items-center gap-2 px-4 py-2 font-medium transition-colors hover:bg-blue-600 hover:text-white">
                <x-heroicon-o-question-mark-circle class="size-4" />
                About me
            </a>

            <a href="https://github.com/benjamincrozat/blog-v5" target="_blank" class="flex items-center gap-2 px-4 py-2 font-medium transition-colors hover:bg-blue-600 hover:text-white">
                <x-iconoir-git-fork class="size-4" />
                Fork the source code
            </a>

            <div class="h-px my-2 bg-black/10"></div>

            <div class="px-4 py-2 text-xs font-bold tracking-widest uppercase">Follow me</div>

            <a href="https://github.com/benjamincrozat" target="_blank" class="flex items-center gap-2 px-4 py-2 font-medium transition-colors hover:bg-blue-600 hover:text-white">
                <x-iconoir-github class="size-4" />
                GitHub
            </a>

            <a href="https://www.linkedin.com/in/benjamincrozat" target="_blank" class="flex items-center gap-2 px-4 py-2 font-medium transition-colors hover:bg-blue-600 hover:text-white">
                <x-iconoir-linkedin class="size-4" />
                LinkedIn
            </a>

            <a href="https://x.com/benjamincrozat" target="_blank" class="flex items-center gap-2 px-4 py-2 font-medium transition-colors hover:bg-blue-600 hover:text-white">
                <x-iconoir-x class="size-4" />
                X
            </a>
        </div>
    </div>
</nav>

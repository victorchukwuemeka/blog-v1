<nav {{ $attributes->class('flex items-center gap-6 md:gap-8 font-normal text-xs') }}>
    <a
        wire:navigate
        href="{{ route('home') }}"
        data-pirsch-event="Clicked the logo"
        class="flex gap-3 items-center text-black transition-colors hover:text-blue-600"
    >
        <div class="relative">
            <x-icon-logo class="h-9 fill-current" />

            <div class="grid absolute right-[-.65rem] bottom-[-.65rem] place-items-center bg-white rounded-full size-5">
                <x-heroicon-s-home class="h-[.9rem] text-current" />
            </div>
        </div>

        <span class="hidden text-base font-bold tracking-widest uppercase md:inline">
            benjamincrozat.com
        </span>
    </a>

    <div class="grow"></div>

    <x-nav.item
        active-icon="heroicon-s-fire"
        icon="heroicon-o-fire"
        href="{{ route('posts.index') }}"
    >
        Latest
    </x-nav.item>

    <x-nav.item
        active-icon="heroicon-s-link"
        icon="heroicon-o-link"
        href="{{ route('links.index') }}"
    >
        Links
    </x-nav.item>

    <x-nav.item
        active-icon="heroicon-s-gift"
        icon="heroicon-o-gift"
        href="{{ route('deals') }}"
    >
        For you
    </x-nav.item>

    @auth
        <x-dropdown>
            <x-slot:btn
                data-pirsch-event='Clicked "Account"'
            >
                <img
                    src="{{ auth()->user()->avatar }}"
                    alt="{{ auth()->user()->name }}'s GitHub avatar"
                    class="mx-auto rounded-full size-6 md:size-7"
                />

                Account
            </x-slot>

            <x-slot:items>
                <div class="px-4 py-2">
                    {{ auth()->user()->name }}
                </div>

                <x-dropdown.divider />

                @if (auth()->user()->isAdmin())
                    <x-dropdown.item
                        icon="heroicon-o-adjustments-horizontal"
                        href="{{ route('filament.admin.pages.dashboard') }}"
                        data-pirsch-event='Clicked "Admin"'
                    >
                        Admin
                    </x-dropdown.item>

                    <x-dropdown.item
                        icon="icon-horizon"
                        href="{{ route('horizon.index') }}"
                        data-pirsch-event='Clicked "Horizon"'
                    >
                        Horizon
                    </x-dropdown.item>
                @endif

                <x-dropdown.divider />

                <x-dropdown.item
                    icon="heroicon-o-chat-bubble-oval-left"
                    wire:navigate
                    href="{{ route('user.comments') }}"
                    data-pirsch-event='Clicked "Your comments"'
                >
                    Your comments
                </x-dropdown.item>

                <x-dropdown.item
                    icon="heroicon-o-link"
                    wire:navigate
                    href="{{ route('user.links') }}"
                    data-pirsch-event='Clicked "Your links"'
                >
                    Your links
                </x-dropdown.item>

                <x-dropdown.item
                    icon="heroicon-o-arrow-right-end-on-rectangle"
                    destructive
                    form="logout-form"
                    data-pirsch-event='Clicked "Log out"'
                >
                    Log out
                </x-dropdown.item>

                <form method="POST" action="{{ route('logout') }}" id="logout-form" class="hidden">
                    @csrf
                </form>
            </x-slot>
        </x-dropdown>
    @else
        <x-nav.item
            no-wire-navigate
            href="{{ route('auth.redirect') }}"
            icon="iconoir-github"
            data-pirsch-event='Clicked "Sign in"'
        >
            Sign in
        </x-nav.item>
    @endauth

    <x-dropdown>
        <x-slot:btn
            data-pirsch-event='Clicked "More"'
        >
            <x-heroicon-o-ellipsis-horizontal
                class="mx-auto transition-transform size-6 md:size-7"
                x-bind:class="{ 'rotate-90': open }"
            />
            More
        </x-slot>

        <x-slot:items>
            <x-dropdown.divider>
                More
            </x-dropdown.divider>

            <x-dropdown.item
                icon="heroicon-o-tag"
                wire:navigate
                href="{{ route('categories.index') }}"
                data-pirsch-event='Clicked "Categories"'
            >
                Categories
            </x-dropdown.item>

            <x-dropdown.item
                icon="heroicon-o-megaphone"
                wire:navigate
                href="{{ route('advertise') }}"
                data-pirsch-event='Clicked "Advertise"'
            >
                Advertise
            </x-dropdown.item>

            <x-dropdown.item
                icon="heroicon-o-question-mark-circle"
                href="{{ route('home') }}#about"
                data-pirsch-event='Clicked "About me"'
            >
                About me
            </x-dropdown.item>

            <x-dropdown.item
                icon="heroicon-o-envelope"
                href="mailto:hello@benjamincrozat.com"
                data-pirsch-event='Clicked "Contact me"'
            >
                Contact me
            </x-dropdown.item>

            <x-dropdown.divider>
                Freebies
            </x-dropdown.divider>

            <x-dropdown.item
                icon="iconoir-git-fork"
                description="This blog is open source and the codebase becomes bigger fast. There's a lot to learn and this is free."
                href="https://github.com/benjamincrozat/blog-v5"
                target="_blank"
                data-pirsch-event='Clicked "Fork the source code"'
            >
                Fork the source code
            </x-dropdown.item>

            <x-dropdown.divider>
                Follow me
            </x-dropdown.divider>

            <x-dropdown.item
                icon="heroicon-o-rss"
                href="{{ route('feeds.main') }}"
                data-pirsch-event='Clicked "Atom feed"'
            >
                Atom feed
            </x-dropdown.item>

            <x-dropdown.item
                icon="iconoir-github"
                href="https://github.com/benjamincrozat"
                target="_blank"
                data-pirsch-event='Clicked "GitHub"'
            >
                GitHub
            </x-dropdown.item>

            <x-dropdown.item
                icon="iconoir-linkedin"
                href="https://www.linkedin.com/in/benjamincrozat"
                target="_blank"
                data-pirsch-event='Clicked "LinkedIn"'
            >
                LinkedIn
            </x-dropdown.item>

            <x-dropdown.item
                icon="iconoir-x"
                href="https://x.com/benjamincrozat"
                target="_blank"
                data-pirsch-event='Clicked "X"'
            >
                X
            </x-dropdown.item>
        </x-slot>
    </x-dropdown>
</nav>

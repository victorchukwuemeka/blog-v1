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
                        href="{{ route('filament.admin.pages.dashboard') }}"
                        data-pirsch-event='Clicked "Admin"'
                    >
                        <x-heroicon-o-adjustments-horizontal class="size-4" />
                        Admin
                    </x-dropdown.item>

                    <x-dropdown.item
                        href="{{ route('horizon.index') }}"
                        data-pirsch-event='Clicked "Horizon"'
                    >
                        <x-icon-horizon class="size-4" />
                        Horizon
                    </x-dropdown.item>
                @endif

                <x-dropdown.divider />

                <x-dropdown.item
                    wire:navigate
                    href="{{ route('user.comments') }}"
                    data-pirsch-event='Clicked "Your comments"'
                >
                    <x-heroicon-o-chat-bubble-oval-left class="size-4" />
                    Your comments
                </x-dropdown.item>

                <x-dropdown.item
                    wire:navigate
                    href="{{ route('user.links') }}"
                    data-pirsch-event='Clicked "Your links"'
                >
                    <x-heroicon-o-link class="size-4" />
                    Your links
                </x-dropdown.item>

                <x-dropdown.item
                    destructive
                    form="logout-form"
                    data-pirsch-event='Clicked "Log out"'
                >
                    <x-heroicon-o-arrow-right-end-on-rectangle class="size-4" />
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
                wire:navigate
                href="{{ route('categories.index') }}"
                data-pirsch-event='Clicked "Categories"'
            >
                <x-heroicon-o-tag class="size-4" />
                Categories
            </x-dropdown.item>

            <x-dropdown.item
                wire:navigate
                href="{{ route('advertise') }}"
                data-pirsch-event='Clicked "Advertise"'
            >
                <x-heroicon-o-megaphone class="size-4" />
                Advertise
            </x-dropdown.item>

            <x-dropdown.item
                href="{{ route('home') }}#about"
                data-pirsch-event='Clicked "About me"'
            >
                <x-heroicon-o-question-mark-circle class="size-4" />
                About me
            </x-dropdown.item>

            <x-dropdown.item
                href="mailto:hello@benjamincrozat.com"
                data-pirsch-event='Clicked "Contact me"'
            >
                <x-heroicon-o-envelope class="size-4" />
                Contact me
            </x-dropdown.item>

            <x-dropdown.divider>
                Code and free tools
            </x-dropdown.divider>

            <x-dropdown.item
                href="https://github.com/benjamincrozat/blog-v5"
                target="_blank"
                data-pirsch-event='Clicked "Fork the source code"'
            >
                <x-iconoir-git-fork class="size-4" />
                Fork the source code
            </x-dropdown.item>

            <x-dropdown.divider>
                Follow me
            </x-dropdown.divider>

            <x-dropdown.item
                href="{{ route('feeds.main') }}"
                data-pirsch-event='Clicked "Atom feed"'
            >
                <x-heroicon-o-rss class="size-4" />
                Atom feed
            </x-dropdown.item>

            <x-dropdown.item
                href="https://github.com/benjamincrozat"
                target="_blank"
                data-pirsch-event='Clicked "GitHub"'
            >
                <x-iconoir-github class="size-4" />
                GitHub
            </x-dropdown.item>

            <x-dropdown.item
                href="https://www.linkedin.com/in/benjamincrozat"
                target="_blank"
                data-pirsch-event='Clicked "LinkedIn"'
            >
                <x-iconoir-linkedin class="size-4" />
                LinkedIn
            </x-dropdown.item>

            <x-dropdown.item
                href="https://x.com/benjamincrozat"
                target="_blank"
                data-pirsch-event='Clicked "X"'
            >
                <x-iconoir-x class="size-4" />
                X
            </x-dropdown.item>
        </x-slot>
    </x-dropdown>
</nav>

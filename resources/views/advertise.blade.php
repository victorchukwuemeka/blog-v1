<x-app
    title="Advertise to {{ Number::format($visitors) }} developers"
>
    <div class="container text-center md:text-xl xl:max-w-(--breakpoint-lg)">
        <img
            loading="lazy"
            src="{{ Vite::asset('resources/img/icons/megaphone.png') }}"
            class="mx-auto mb-8 h-24 md:h-28 lg:h-32"
        />

        <h1 class="text-3xl font-medium tracking-tight text-black lg:text-4xl xl:text-7xl">
            Advertise to <span class="text-blue-600">{{ Number::format($visitors) }}</span>&nbsp;developers
        </h1>

        <p class="mt-2 text-lg text-gray-800 md:mt-4 md:text-xl lg:text-2xl">
            This is the right place to show off your product.
        </p>

        <x-btn
            primary
            size="md"
            href="mailto:hello@benjamincrozat.com"
            class="table mx-auto mt-8 lg:mt-12"
        >
            Get in touch
        </x-btn>
    </div>

    <x-section
        title="Trusted by"
        class="mt-24 text-center"
    >
        <div class="flex flex-wrap gap-y-4 gap-x-8 justify-center items-center px-4 md:gap-x-12 lg:gap-x-16">
            <x-icon-kinsta class="flex-none -translate-y-px h-[1.15rem] sm:h-6" />
            <div class="text-2xl font-bold text-red-600 sm:text-3xl">larajobs</div>
            <x-icon-meilisearch class="flex-none h-6 translate-y-px sm:h-7" />
            <x-icon-sevalla class="flex-none h-9 sm:h-10" />
        </div>
    </x-section>

    <x-section
        title="The past 30 days on my blog"
        class="mt-24 xl:max-w-(--breakpoint-lg)"
    >
        <div class="grid grid-cols-2 gap-2 mt-6 text-center md:text-xl">
            <div class="p-2 text-black bg-gray-50 rounded-xl md:p-4">
                <x-heroicon-o-user class="mx-auto mb-2 h-8 text-gray-600 md:h-10 lg:h-12" />
                <div class="text-3xl font-medium md:text-5xl">{{ Number::format($visitors) }}</div>
                <div class="md:text-xl lg:text-xl">visitors</div>
            </div>

            <div class="p-2 text-black bg-gray-50 rounded-xl md:p-4">
                <x-heroicon-o-window class="mx-auto mb-2 h-8 text-gray-600 md:h-10 lg:h-12" />
                <div class="text-3xl font-medium md:text-5xl">{{ $views }}</div>
                <div class="md:text-xl lg:text-xl">page views</div>
            </div>

            <div class="p-2 text-black bg-gray-50 rounded-xl md:p-4">
                <x-heroicon-o-user-group class="mx-auto mb-2 h-8 text-gray-600 md:h-10 lg:h-12" />
                <div class="text-3xl font-medium md:text-5xl">{{ $sessions }}</div>
                <div class="md:text-xl lg:text-xl">sessions</div>
            </div>

            <div class="p-2 text-black bg-gray-50 rounded-xl md:p-4">
                <x-heroicon-o-computer-desktop class="mx-auto mb-2 h-8 text-gray-600 md:h-10 lg:h-12" />
                <div class="text-3xl font-medium md:text-5xl">{{ $desktop }}%</div>
                <div class="md:text-xl lg:text-xl">on desktop</div>
            </div>
        </div>
    </x-section>

    <x-section
        title="How to show off your product"
        class="mt-24 md:max-w-screen-sm"
    >
        <div class="p-4 rounded-xl bg-gray-100/50 md:p-8 md:text-xl">
            <h3 class="text-xl font-medium md:text-2xl">
                Sponsored article
            </h3>

            <ul class="grid gap-2 mt-4">
                <li class="flex gap-2 items-center">
                    <x-heroicon-o-check-circle class="text-emerald-600 size-6" />
                    Featured on top for a week
                </li>

                <li class="flex gap-2 items-center">
                    <x-heroicon-o-check-circle class="text-emerald-600 size-6" />
                    Access to {{ Number::format($visitors) }} monthly developers
                </li>

                <li class="flex gap-2 items-center">
                    <x-heroicon-o-check-circle class="text-emerald-600 size-6" />
                    Lifetime backlink on a DR 51 domain
                </li>

                <li class="flex gap-2 items-center">
                    <x-heroicon-o-check-circle class="text-emerald-600 size-6" />
                    Forever on the blog
                </li>
            </ul>

            <x-btn
                primary
                size="md"
                href="mailto:hello@benjamincrozat.com"
                class="mt-[1.35rem] text-base rounded-lg!"
            >
                Get in touch
            </x-btn>
        </div>
    </x-section>
</x-app>

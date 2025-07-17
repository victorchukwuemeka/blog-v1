<x-app
    :hide-ad="true"
    title="Deals for developers: services, apps, and more"
>
    <section>
        <h1 class="font-bold tracking-widest text-center text-black uppercase text-balance">
            Featured deals
        </h1>

        <p class="px-4 mt-2 leading-tight text-center">These companies are sponsoring my blog.<br class="hidden md:inline" /> Big thanks to them and make sure to check them out!</p>

        <div class="flex overflow-x-auto gap-8 px-4 mt-8 md:justify-center snap-mandatory snap-x">
            @php
            $components = collect([
                'ads.deals.sevalla',
                'ads.deals.vemetric',
            ])->shuffle()->toArray();
            @endphp

            @foreach ($components as $component)
                <x-dynamic-component :$component class="w-[85%] md:w-[45%] lg:w-[45%] xl:w-[40%] snap-start scroll-ml-4 flex-none" />
            @endforeach
        </div>
    </section>

    <x-section title="Latest deals" class="mt-14 md:mt-24">
        <p class="px-4 -mt-6 leading-tight text-center">
            Browse the great deals I gathered from across the web.<br class="hidden md:inline" />
            Services, apps, and all kinds of tools to help you do your job more efficiently.
        </p>

        <div class="grid gap-8 mt-8 md:grid-cols-2">
            <x-deals.item
                name="Fathom Analytics"
                headline="Know who visits your site"
                subheadline="Fathom Analytics is a simple, privacy-focused web analytics. No cookies, ads, or tracking."
                cta="Start free + $10 off"
                cta-color="bg-[#171B18]!"
                href="{{ route('merchants.show', 'fathom-analytics') }}"
                :src="Vite::asset('resources/img/screenshots/fathom-analytics.webp')"
            />

            <x-deals.item
                name="Cloudways"
                headline="Easily deploy PHP web apps"
                subheadline="PHP 8, scalability, Cloudflare, caching, 24/7 support, and more with Cloudways"
                cta="Start free"
                cta-color="bg-[#3641C2]!"
                href="{{ route('merchants.show', 'cloudways-php') }}"
                :src="Vite::asset('resources/img/screenshots/cloudways.webp')"
            />

            <x-deals.item
                name="Mailcoach"
                headline="Send emails to your users"
                subheadline="Self-hosted email marketing built for Laravel developers, by Laravel developers."
                cta="Start free"
                cta-color="bg-[#142C6E]!"
                href="{{ route('merchants.show', 'mailcoach') }}"
                :src="Vite::asset('resources/img/screenshots/mailcoach.webp')"
            />

            <x-deals.item
                name="Wincher"
                headline="Rank higher on Google"
                subheadline="Use Wincher to track and grow your business’s search visibility."
                cta="Start free"
                cta-color="bg-[#F09B4F]!"
                href="{{ route('merchants.show', 'wincher') }}"
                :src="Vite::asset('resources/img/screenshots/wincher.avif')"
            />

            <x-deals.item
                name="DigitalOcean"
                headline="Host your web apps on a VPS"
                subheadline="DigitalOcean provides affordable, scalable, and reliable VPS hosting."
                cta="Start with $200 free credit"
                href="{{ route('merchants.show', 'digitalocean') }}"
                :src="Vite::asset('resources/img/screenshots/digitalocean.webp')"
            />
        </div>
    </x-section>
</x-app>

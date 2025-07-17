<x-app
    :hide-ad="true"
    title="Deals for developers: services, apps, and more"
>
    <section>
        <h1 class="font-bold tracking-widest text-center text-black uppercase text-balance">
            Featured deals
        </h1>

        <div class="flex overflow-x-auto gap-4 px-4 mt-8 md:justify-center snap-mandatory snap-x">
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
        <div class="grid gap-16 md:gap-8 md:grid-cols-2 xl:grid-cols-3">
            <x-deals.item
                name="Fathom Analytics"
                headline="Know who visits your site"
                subheadline="Fathom Analytics is a simple, privacy-focused web analytics. No cookies, ads, or tracking."
                cta="Start free + $10 off"
                href="{{ route('merchants.show', 'fathom-analytics') }}"
                :src="Vite::asset('resources/img/screenshots/fathom-analytics.webp')"
            />

            <x-deals.item
                name="Cloudways"
                headline="Easily deploy PHP web applications"
                subheadline="PHP 8, scalability, Cloudflare, caching, 24/7 support, and more with Cloudways"
                cta="Start free"
                href="{{ route('merchants.show', 'cloudways-php') }}"
                :src="Vite::asset('resources/img/screenshots/cloudways.webp')"
            />

            <x-deals.item
                name="Mailcoach"
                headline="Send emails to your users"
                subheadline="Mailcoach is a self-hosted email marketing tool for newsletters, automations, and transactional emails, built for Laravel developers."
                cta="Start free"
                href="{{ route('merchants.show', 'mailcoach') }}"
                :src="Vite::asset('resources/img/screenshots/mailcoach.webp')"
            />

            <x-deals.item
                name="Wincher"
                headline="Rank higher on Google"
                subheadline="Use Wincher to track and grow your business’s search visibility."
                cta="Start free"
                href="{{ route('merchants.show', 'wincher') }}"
                :src="Vite::asset('resources/img/screenshots/wincher.avif')"
            />

            <x-deals.item
                name="DigitalOcean"
                headline="Host your web applications on a VPS"
                subheadline="DigitalOcean provides affordable, scalable, and reliable VPS hosting."
                cta="Start with $200 free credit"
                href="{{ route('merchants.show', 'digitalocean') }}"
                :src="Vite::asset('resources/img/screenshots/digitalocean.webp')"
            />
        </div>
    </x-section>
</x-app>

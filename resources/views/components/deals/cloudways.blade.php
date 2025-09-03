<x-deals.item
    name="Cloudways"
    headline="Easily deploy PHP web apps"
    cta="Start free"
    cta-color="bg-[#3641C2]!"
    href="{{ route('merchants.show', 'cloudways-php') }}"
    :src="Vite::asset('resources/img/screenshots/cloudways.webp')"
>
    <x-slot:subheadline>
PHP 8, scalability, Cloudflare, caching, 24/7 support, and more with Cloudways.

Get **30% off for 5 months** using *SUMMER305*.
    </x-slot:subheadline>
</x-deals.item>

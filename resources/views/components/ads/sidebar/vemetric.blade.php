<a
    {{
        $attributes
            ->class('grid gap-4 p-4 !pt-6 leading-tight bg-blue-50/75 text-blue-900 rounded-xl')
            ->merge([
                'href' => route('redirect-to-advertiser', [
                    'slug' => 'vemetric',
                    'utm_medium' => 'sidebar',
                ]),
                'target' => '_blank',
            ])
    }}
>
    <img
        loading="lazy"
        src="https://www.gravatar.com/avatar/d58b99650fe5d74abeb9d9dad5da55ad?s=256"
        alt="Benjamin Crozat"
        class="mx-auto h-10 rounded-full"
    />

    <p class="-mt-2 leading-tight text-center text-blue-950">
        <strong class="font-semibold">“Let's ditch<br /> bloated analytics!”</strong>
    </p>

    <p>Vemetric plays nice with PHP and others to give you real-time stats.</p>

    <ul class="grid gap-1 -mt-2 ml-3 list-disc list-inside">
        <li>It's also open-source</li>
        <li>No cookies, no consent banner</li>
        <li>EU-hosted, GDPR-ready</li>
        <li><strong class="font-medium text-blue-950">Early adopter price: $5/mo</strong></li>
    </ul>

    <img
        loading="lazy"
        src="{{ Vite::asset('resources/img/screenshots/vemetric.webp') }}"
        alt="Vemetric's dashboard"
        class="z-10 mt-5 rounded ring-1 shadow-lg transition-transform rotate-1 scale-115 md:scale-125 hover:rotate-0 hover:scale-125 md:hover:scale-150 shadow-blue-900/10 ring-blue-900/10"
    />

    <x-btn primary class="w-full mt-6 text-center !rounded-md cursor-pointer">
        Start for free
    </x-btn>
</a>

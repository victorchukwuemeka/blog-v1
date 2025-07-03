<a
    {{
        $attributes
            ->class('grid sticky top-4 gap-4 p-4 leading-tight bg-gray-50 rounded-xl')
            ->merge([
                'href' => 'https://vemetric.com?utm_source=benjamin_crozat&utm_medium=sidebar',
                'target' => '_blank',
            ])
    }}
>
    <img src="https://www.gravatar.com/avatar/d58b99650fe5d74abeb9d9dad5da55ad?s=256" alt="Benjamin Crozat" class="mx-auto h-12 rounded-full" />

    <p class="text-center text-lg/tight"><strong class="font-semibold text-black">“Let's ditch bloated analytics!”</strong></p>

    <p>Vemetric plays nice with PHP, JavaScript, Python, and more, to give you real-time stats.</p>

    <ul class="grid gap-1 -mt-2 ml-3 list-disc list-inside">
        <li>It's also open-source</li>
        <li>No cookies, no consent banner</li>
        <li>EU-hosted, GDPR-ready</li>
        <li>Clean dashboard</li>
        <li><strong class="font-medium text-black">Early adopter price: $5/mo</strong></li>
    </ul>

    <img src="{{ Vite::asset('resources/img/screenshots/vemetric.webp') }}" alt="Vemetric's dashboard" class="rounded" />

    <x-btn primary class="w-full mt-2 text-center !rounded-md cursor-pointer">
        Start for free
    </x-btn>
</a>
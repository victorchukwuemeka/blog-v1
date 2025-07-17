<x-ads.deals {{
    $attributes
        ->class('border-orange-300')
        ->merge([
            'href' => route('redirect-to-advertiser', [
                'slug' => 'sevalla',
                'utm_source' => 'deals',
            ]),
        ])
}}>
    <x-icon-sevalla class="self-start h-10 md:h-12" />

    <p class="flex-grow mt-4 leading-tight text-orange-700 text-balance">Deploy and scale your web applications securely with ease.</p>

    <x-btn
        primary
        class="mt-8 cursor-pointer bg-[#FA7216]! rounded-md!"
    >
        $50 free credits
    </x-btn>
</x-ads.deals>

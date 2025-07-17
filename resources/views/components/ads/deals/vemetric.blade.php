<x-ads.deals
    {{
        $attributes
            ->class('text-violet-900 border-violet-300')
            ->merge([
                'href' => route('redirect-to-advertiser', [
                    'slug' => 'vemetric',
                    'utm_source' => 'deals',
                ]),
            ])
    }}
>
    <x-icon-vemetric class="self-start h-10 md:h-12" />

    <p class="flex-grow mt-4 leading-tight text-balance">Vemetric is a simple, open-source, privacy-first analytics tool that tracks the full user journey—from first visit to feature adoption.</p>

    <x-btn
        primary
        class="mt-8 cursor-pointer bg-violet-500! rounded-md!"
    >
        Start for free
    </x-btn>
</x-ads.deals>

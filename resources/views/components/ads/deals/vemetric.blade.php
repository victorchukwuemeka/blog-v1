<x-ads.deals
    {{
        $attributes
            ->class('text-violet-900 bg-violet-50')
            ->merge([
                'href' => route('redirect-to-advertiser', [
                    'slug' => 'vemetric',
                    'utm_source' => 'deals',
                ]),
                'name' => 'Vemetric',
                'svgLogo' => 'icon-vemetric',
                'descriptionColor' => 'text-violet-900',
                'description' => 'Vemetric is a simple, open-source, privacy-first analytics tool that tracks the full user journey.',
                'cta' => 'Start free',
                'ctaColor' => 'bg-violet-500!',
                'screenshot' => Vite::asset('resources/img/screenshots/vemetric.webp'),
            ])
    }}
/>

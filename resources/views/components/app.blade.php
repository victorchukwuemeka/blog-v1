@props([
    'canonical' => $canonical ?? url()->current(),
    'description' => '',
    'image' => '',
    'title' => $title ?? config('app.name'),
])

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />

        <title>{{ $title }}</title>

        <meta name="title" content="{{ $title }}" />
        <meta name="description" content="{{ $description }}" />

        <meta property="og:type" content="website" />
        <meta property="og:url" content="{{ url()->current() }}" />
        <meta property="og:title" content="{{ $title }}" />
        <meta property="og:description" content="{{ $description }}" />
        <meta property="og:image" content="{{ $image }}" />

        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:url" content="{{ url()->current() }}" />
        <meta name="twitter:title" content="{{ $title }}" />
        <meta name="twitter:description" content="{{ $description }}" />
        <meta name="twitter:image" content="{{ $image }}" />

        <livewire:styles />

        @vite('resources/css/app.css')

        @if (config('app.env') === 'production')
            <script
                defer
                src="https://api.pirsch.io/pa.js"
                id="pianjs"
                data-code="5N2hIsUQsCVX1LQtvPdJ3AGwQZHGxtt5"
                data-disable-page-views
            ></script>
        @endif

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@200..800&display=swap" />

        <link rel="icon" type="image/png" href="{{ Vite::asset('resources/img/favicon-96x96.png') }}" sizes="96x96" />
        <link rel="icon" type="image/svg+xml" href="{{ Vite::asset('resources/img/favicon.svg') }}" />
        <link rel="shortcut icon" href="{{ Vite::asset('resources/img/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="180x180" href="{{ Vite::asset('resources/img/apple-touch-icon.png') }}" />

        <link rel="canonical" href="{{ $canonical }}" />

        <x-feed-links />
    </head>
    <body {{ $attributes->class('font-light text-gray-600') }}>
        <div class="flex flex-col min-h-screen">
            @empty($hideAd)
                <x-ads.top.sevalla
                    class="opacity-0 delay-[300ms] transition-[opacity,translate] duration-[600ms] -translate-y-4"
                    x-bind:class="{
                        'opacity-100 translate-y-0': show,
                    }"
                    x-data="{ show: false }"
                    x-intersect:enter="show = true"
                />
            @endempty

            @empty($hideNavigation)
                <header class="container mt-4 xl:max-w-(--breakpoint-lg)">
                    <x-nav />
                </header>
            @endempty

            <main @class([
                'grow',
                'py-12 md:py-16' => empty($hideNavigation),
            ])>
                {{ $slot }}
            </main>

            @empty($hideFooter)
                <x-footer />
            @endempty
        </div>

        <x-status />

        @livewireScriptConfig

        @vite('resources/js/app.js')
    </body>
</html>

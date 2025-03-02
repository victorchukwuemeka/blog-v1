@props([
    'canonical' => url()->current(),
    'description' => '',
    'image' => '',
    'title' => config('app.name'),
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

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" />

        <link rel="canonical" href="{{ $canonical }}">
    </head>
    <body {{ $attributes->class('font-light text-gray-600') }}>
        <header class="container mt-4 xl:max-w-screen-lg">
            <x-nav />
        </header>

        <main class="mt-8">
            {{ $slot }}
        </main>

        <div class="mt-8 bg-gray-100 md:mt-16">
            <footer class="container py-8">
                <nav class="flex items-center justify-center gap-8">
                    <a wire:navigate href="{{ route('home') }}" class="font-medium">Home</a>
                    <a wire:navigate href="{{ route('posts.index') }}" class="font-medium">Latest</a>
                    <a href="{{ route('home') }}#about" class="font-medium">About</a>
                </nav>

                <p class="mt-6 text-center text-gray-400">Please don't steal my content. © {{ date('Y') }} blah blah blah.</p>
            </footer>
        </div>

        @livewireScriptConfig

        @vite('resources/js/app.js')
    </body>
</html>

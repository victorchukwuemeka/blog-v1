<div {{ $attributes->class('bg-gray-100') }}>
    <footer class="container py-8 lg:max-w-(--breakpoint-md)">
        <nav class="grid grid-cols-2 sm:grid-cols-6 sm:place-items-center *:font-medium gap-x-8 gap-y-2">
            <a wire:navigate href="{{ route('home') }}">Home</a>
            <a wire:navigate href="{{ route('posts.index') }}">Articles</a>
            <a wire:navigate href="{{ route('categories.index') }}">Categories</a>
            <a wire:navigate href="{{ route('links.index') }}">Links</a>
            <a href="{{ route('home') }}#about">About</a>
            <a href="mailto:hello@benjamincrozat.com">Contact</a>
        </nav>

        <p class="mt-8 text-center">Hosted on <a href="{{ route('merchants.show', 'digitalocean') }}" target="_blank" class="font-medium underline decoration-1 decoration-black/30 underline-offset-4">DigitalOcean</a>, managed by <a href="{{ route('merchants.show', 'ploi') }}" target="_blank" class="font-medium underline decoration-1 decoration-black/30 underline-offset-4">Ploi</a>, and analytics provided by <a href="{{ route('merchants.show', 'pirsch-analytics') }}" target="_blank" class="font-medium underline decoration-1 decoration-black/30 underline-offset-4">Pirsch</a>.</p>

        <p class="mt-8 text-center text-gray-400">Please don't steal my content. © {{ date('Y') }} blah blah blah.</p>
    </footer>
</div>

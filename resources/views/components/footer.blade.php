<div {{ $attributes->class('bg-gray-100') }}>
    <footer class="container py-8">
        <nav class="flex items-center justify-center gap-8">
            <a wire:navigate href="{{ route('home') }}" class="font-medium">Home</a>
            <a wire:navigate href="{{ route('posts.index') }}" class="font-medium">Latest</a>
            <a wire:navigate href="{{ route('links.index') }}" class="font-medium">Links</a>
            <a href="{{ route('home') }}#about" class="font-medium">About</a>
        </nav>

        <p class="mt-6 text-center text-gray-400">Please don't steal my content. © {{ date('Y') }} blah blah blah.</p>
    </footer>
</div>

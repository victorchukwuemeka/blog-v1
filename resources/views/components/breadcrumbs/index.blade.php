<div {{ $attributes->class('flex items-center gap-2') }}>
    <a wire:navigate href="{{ route('home') }}" class="grid text-sm transition-colors bg-gray-100 rounded hover:bg-gray-200 place-items-center size-6">
        /
    </a>

    {{ $slot }}
</div>

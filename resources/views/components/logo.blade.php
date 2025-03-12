<a
    {{ $attributes->class('flex items-center gap-3')->merge([
        'wire:navigate',
        'href' => route('home'),
    ]) }}
>
    <div class="grid bg-black place-items-center size-9 md:size-10 rounded-[.65rem] md:rounded-xl">
        <x-heroicon-s-bold class="text-white size-5 md:size-6" />
    </div>

    <div class="font-medium tracking-tighter sr-only sm:not-sr-only sm:text-lg md:text-xl">
        benjamincrozat.com
    </div>
</a>

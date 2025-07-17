<a
    href="{{ $href }}"
    target="_blank"
    class="group"
>
    <div class="flex overflow-hidden flex-col h-full rounded-xl ring-1 shadow-md ring-black/10">
        <div class="flex flex-col flex-grow p-4 md:p-6">
            <p class="text-xl font-medium tracking-tight text-black">
                {{ $headline }}
            </p>

            <div class="flex-grow mt-2 leading-tight prose text-balance">
                {!! Str::markdown($subheadline) !!}
            </div>

            <x-btn
                primary
                class="mt-6 self-start !rounded-md cursor-pointer"
            >
                {{ $cta }}
            </x-btn>
        </div>

        <img
            loading="lazy"
            src="{{ $src }}"
            alt="{{ $name }}"
            class="object-cover object-top aspect-video"
        />
    </div>
</a>

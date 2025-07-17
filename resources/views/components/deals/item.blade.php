<a
    {{
        $attributes
            ->class('bg-gray-100/75 flex rounded-xl overflow-hidden transition-opacity hover:opacity-50 leading-tight')
            ->merge([
                'href' => $href,
                'target' => '_blank',
            ])
    }}
>
    <div class="flex flex-col flex-1 p-4 md:p-6">
        <p class="text-xl font-medium tracking-tight text-black">
            {{ $headline }}
        </p>

        <p class="flex-grow mt-4 leading-tight sm:text-balance">
            {!! Str::markdown($subheadline) !!}
        </p>

        <x-btn
            primary
            class="mt-8 self-start cursor-pointer rounded-md! {{ $ctaColor ?? '' }}"
        >
            {{ $cta }}
        </x-btn>
    </div>

    <div class="relative flex-none w-[20%] sm:w-[33.33%] lg:flex-1">
        <img
            loading="lazy"
            src="{{ $src }}"
            alt="{{ $name }}"
            class="object-cover absolute inset-0 w-full h-full object-top-left"
        />
    </div>
</a>

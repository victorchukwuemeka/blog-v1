<a
    {{
        $attributes
            ->class('flex rounded-xl overflow-hidden transition-opacity hover:opacity-50 leading-tight')
            ->merge([
                'target' => '_blank',
            ])
    }}
>
    <div class="flex flex-col flex-1 p-4 md:p-6">
        <x-dynamic-component :component="$svgLogo" class="self-start h-10" />

        <p class="flex-grow mt-4 leading-tight sm:text-balance {{ $descriptionColor }}">
            {{ $description }}
        </p>

        <x-btn
            primary
            class="mt-8 self-start cursor-pointer rounded-md! {{ $ctaColor }}"
        >
            {{ $cta }}
        </x-btn>
    </div>

    <div class="relative flex-none w-[20%] sm:w-[33.33%] lg:flex-1">
        <img
            loading="lazy"
            src="{{ $screenshot }}"
            alt="{{ $name }}"
            class="object-cover absolute inset-0 w-full h-full object-top-left"
        />
    </div>
</a>

<section {{ $attributes->class('grid gap-8 container scroll-mt-4') }}>
    @if (! empty($title))
        <h1 @class([
            'font-bold tracking-widest text-center text-black uppercase text-balance',
            'text-xl/tight md:text-2xl/tight' => ! empty($bigTitle),
        ])>
            {!! $title !!}
        </h1>
    @endif

    {{ $slot }}
</section>

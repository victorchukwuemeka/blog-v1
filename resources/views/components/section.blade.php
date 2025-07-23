@props([
    'title' => null,
    'bigTitle' => false,
])

<section {{ $attributes->class('container scroll-mt-4') }}>
    @if (! empty($title))
        <x-heading :big="$bigTitle" class="mb-8">
            {!! $title !!}
        </x-heading>
    @endif

    {{ $slot }}
</section>

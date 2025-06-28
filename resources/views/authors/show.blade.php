<x-app title="About {{ $author->name }}">
    <x-section title="About {{ $author->name }}" class="lg:max-w-(--breakpoint-md) mt-0 md:mt-8">
        <x-prose>
            <img src="{{ $author->avatar }}" alt="{{ $author->name }}" class="rounded-full! float-right ml-4 md:ml-6 mt-2 size-20 md:size-24">
            {!! Str::markdown($author->biography) !!}
        </x-prose>
    </x-section>
</x-app>
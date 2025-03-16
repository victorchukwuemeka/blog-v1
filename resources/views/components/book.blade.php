@props(['book'])

<a href="{{ $book['link'] }}" target="_blank" {{ $attributes->class('text-sm/tight md:text-base/tight') }}>
    <img src="{{ Vite::asset($book['image']) }}" alt='Cover for "{{ $book['name'] }}"' class="rounded-lg aspect-[3/4] max-h-[90px] md:max-h-[100px]" />

    <div class="mt-2 font-medium truncate">
        {{ $book['name'] }}
    </div>

    <div class="truncate">{{ $book['author'] }}</div>

    <div class="inline-block md:text-sm md:rounded-md px-[.65rem] py-[.35rem] text-xs rounded mt-3 font-medium text-white transition-colors bg-blue-600 hover:bg-blue-500 md:mt-4">
        Check the book
    </div>
</a>
